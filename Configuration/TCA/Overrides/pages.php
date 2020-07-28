<?php
defined('TYPO3_MODE') || die();

call_user_func(function()
{
    /**
     * Temporary variables
     */
    $extensionKey = 'gowest_config';

    /**
     * Default PageTS for GowestConfig
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/All.tsconfig',
        'GO.WEST Config'
    );
});

$pagesColumn = array(
    'tx_gowestconfig_summary' => array (
        'exclude' => 0,
        'label' => 'Summary',
        'config' => array (
            'type' => 'text',
            'cols' => 30,
            'rows' => 2
        )
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    $pagesColumn
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'pages',
    'title',
    'tx_gowestconfig_summary,--linebreak--',
    'before:title'
);