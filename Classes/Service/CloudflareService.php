<?php

namespace Gowest\GowestConfig\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use Psr\Http\Message\RequestFactoryInterface;

use TYPO3\CMS\Core\Site\SiteFinder;

class CloudflareService
{
    public function __construct()
    {
        $this->requestFactory = GeneralUtility::makeInstance(RequestFactoryInterface::class);
        $this->siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $this->dbConnections = [
            'pages'                     => GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages'),
            'tt_content'                => GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content'),
        ];
    }

    protected function getCurrentConfig($rootId): ?array
    {   
        /**
         * Site Configuration:
         *  gowest_config:
         *    cloudflare:
         *      allowPurgeAll: false
         *      apiKey: API_KEY
         *      zoneId: ZONE_ID
         *      urls:
         *        - https://riederalm.com
         *        - https://riederalm.com/api
         *        - https://api.riederalm.com
         **/
        $site = $this->siteFinder->getSiteByPageId($rootId);
        if (!$site) {
            return null;
        }
        $siteConfig = $site->getConfiguration();
        $config = $siteConfig['gowest_config']['cloudflare'] ?? [];
        if($this->validateConfig($config)) {
            $config['languages'] = $siteConfig['languages'] ?? [];
            return is_array($config) ? $config : null;
        } else {
            return null;
        }
       
    }

    protected function getRootPages($pageIds): array
    {
        $placeholders = implode(',', array_fill(0, count($pageIds), '?'));

        $sql = <<<SQL
            WITH RECURSIVE page_tree AS (
                SELECT
                    p.uid AS start_uid,
                    p.uid,
                    p.pid,
                    p.is_siteroot,
                    p.doktype
                FROM pages p
                WHERE p.uid IN ($placeholders)

                UNION ALL

                SELECT
                    pt.start_uid,
                    p.uid,
                    p.pid,
                    p.is_siteroot,
                    p.doktype
                FROM pages p
                JOIN page_tree pt ON pt.pid = p.uid
                WHERE p.deleted = 0 AND p.hidden = 0
            )
            SELECT
                start_uid,
                uid AS root_uid
            FROM page_tree
            WHERE is_siteroot = 1 AND doktype = 1
        SQL;

        /** @var \Doctrine\DBAL\Statement $stmt */
        $stmt =  $this->dbConnections['pages']->prepare($sql);
        $result = $stmt->executeQuery($pageIds); // binds all `?` placeholders

        $rootPagesResult = $result->fetchAllAssociative();
        $rootPages = [];

        foreach ($rootPagesResult as $item) {
            $root = $item['root_uid'];
            $start = $item['start_uid'];
        
            if (!isset($rootPages[$root])) {
                $rootPages[$root] = [];
            }
        
            $rootPages[$root][] = $start;
        }
        
        return $rootPages;
    }

    protected function getUrls($pageIds, $config): array 
    {
        $placeholders = implode(',', array_fill(0, count($pageIds), '?'));

        $parameters = array_merge($pageIds, $pageIds);

        $langConfig = [];

        foreach ($config['languages'] as $lang) {
            $langConfig[$lang['languageId']] = str_replace('/', '', $lang['base']);
        }

        // Updated query to include default language and translations
        $sql = <<<SQL
            SELECT 
                CASE 
                    WHEN pages.sys_language_uid = 0 THEN CONCAT('/###LANG-ID-0###' , pages.slug)
                    ELSE CONCAT('/###LANG-ID-', LOWER(sys_language.uid), '###', pages.slug)
                END AS slug
            FROM pages
            LEFT JOIN sys_language
                ON sys_language.uid = pages.sys_language_uid
            WHERE 
                (pages.uid IN ($placeholders) OR pages.l10n_parent IN ($placeholders))
                AND pages.deleted = 0 
                AND pages.hidden = 0
        SQL;

        /** @var \Doctrine\DBAL\Statement $stmt */
        $stmt =  $this->dbConnections['pages']->prepare($sql);
        $result = $stmt->executeQuery($parameters); // binds all `?` placeholders

        $slugsRes = $result->fetchAllAssociative();
        $slugs = [];
        foreach ($slugsRes as $item) {
            $slug = $item['slug'];
            if (!in_array($slug, $slugs)) {
                foreach ($langConfig as $langId => $base) {
                    $slug = str_replace('###LANG-ID-' . $langId . '###', $base, $slug);
                }
                $slugs[] = $slug;
            }
        }

        $urls = [];

        if(is_array($config['urls'])) {
            foreach($config['urls'] as $baseUrl) {
                foreach($slugs as $slug) {
                    $urls[] = $baseUrl . $slug;
                }
            }
        }

        return $urls;
    }

    public function purgeUrls($pageIds): void
    {   
        $rootPages = $this->getRootPages($pageIds);

        foreach ($rootPages as $rootId => $pageIdsByRootId) {
            $config = $this->getCurrentConfig($rootId);
            $urls = $this->getUrls($pageIdsByRootId, $config);
            if ($config) {
                $chunks = array_chunk($urls, 30);

                foreach ($chunks as $chunk) {
                    // Example payload
                    $payload = [
                        'files' => $chunk,
                    ];

                    $this->sendPurgeRequest($config['apiKey'], $config['zoneId'], ['files' => $chunk]);
                }
            }
        }
    }
    
    public function purgeEverything(): void
    {   
        $sites = $this->siteFinder->getAllSites();

        $cloudflareConfigs = [];

        foreach($sites as $site) {
            $siteConfig = $site->getConfiguration();
            $config = $siteConfig['gowest_config']['cloudflare'] ?? [];
            $config['allowPurgeAll'] = $config['allowPurgeAll'] ? true : false;
            if($this->validateConfig($config) && $config['allowPurgeAll'] === true) {
                $config['languages'] = $siteConfig['languages'] ?? [];
                $cloudflareConfigs[$site->getRootPageId()] = $config;
            }
        }
        
        if (count($cloudflareConfigs) > 0) {
            foreach ($cloudflareConfigs as $domain => $config) {
                $this->sendPurgeRequest($config['apiKey'], $config['zoneId'], ['purge_everything' => true]);
            }
        }
    }

    protected function sendPurgeRequest(string $apiKey, string $zoneId, array $payload): void
    {
        $client = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Http\RequestFactory::class);
        $response = $client->request(
            'https://api.cloudflare.com/client/v4/zones/' . $zoneId . '/purge_cache',
            'POST',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($payload),
            ]
        );

        if ($response->getStatusCode() === 200) {
            // Cache purge was successful
            $this->cacheWarmup($payload['files'] ?? []);
        }
    }

    protected function validateConfig(array $config): bool
    {
        $requiredKeys = ['apiKey', 'zoneId', 'urls'];

        foreach ($requiredKeys as $key) {
            if (empty($config[$key])) {
                return false;
            }
        }

        if (!is_array($config['urls']) || empty($config['urls'])) {
            return false;
        }

        return true;
    }

    protected function cacheWarmup(array $urls): void
    {
        $multiHandle = curl_multi_init();
        $curlHandles = [];

        foreach ($urls as $url) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_NOBODY => true, // Send HEAD instead of GET
                CURLOPT_TIMEOUT => 1,
                CURLOPT_CONNECT_TIMEOUT => 1,
                CURLOPT_NOSIGNAL => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_HTTPHEADER => [
                    'Connection: close'
                ]
            ]);

            curl_multi_add_handle($multiHandle, $ch);
            $curlHandles[] = $ch;
        }

        $running = null;
        do {
            curl_multi_exec($multiHandle, $running);
        } while ($running > 0);

        foreach ($curlHandles as $ch) {
            curl_multi_remove_handle($multiHandle, $ch);
            curl_close($ch);
        }
        curl_multi_close($multiHandle);
    }

}
