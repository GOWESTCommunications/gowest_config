<?php

namespace Gowest\GowestConfig\Hooks;

use Gowest\GowestConfig\Service\CloudflareService;

class CacheClearHook
{
    public function clearCachePostProc(array $params): void
    {
        // #1 cacheCmd
        // NUMBER => check tags [pageId_454]
        // pages => clear all cache
        // all => clear all cache 

        // #2 table (pages | tt_content)
        // check tags [pageId_454]

        $cloudflareService = new CloudflareService();

        $findTagTables = ['pages', 'tt_content'];
        $cacheCmd = $params['cacheCmd'] ?? '';
        $table = $params['table'] ?? '';

        if(in_array($cacheCmd, ['all', 'pages'], true)) {
            // clear all cache
            $cloudflareService->purgeEverything();
        } else {
            // clear specific cache
            $pageIds = [];
            $tags = $params['tags'] ?? [];

            if (in_array($table, $findTagTables, true)) {
                $tags = array_keys($params['tags'] ?? []);
            }

            foreach ($tags as $tag) {
                if (preg_match('/^pageId_(\d+)$/', $tag, $matches)) {
                    $pageIds[] = (int)$matches[1];
                }
            }

            $pageIds = array_unique($pageIds);

            if(is_array($pageIds) && count($pageIds) > 0) {
                $cloudflareService->purgeUrls($pageIds);
            }
        }
    }
}
