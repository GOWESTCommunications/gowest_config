<?php
defined('TYPO3_MODE') || die();

$ttcontentColumn = array(
    'tx_gowestconfig_gutter' => array (
        'exclude' => 0,
        'label' => 'Gutter',
        'config' => array (
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Default', 'default'],
                ['1px', 'small'],
                ['None', 'none'],
            ],
        )
    ),
    'tx_gowestconfig_summary' => array (
        'exclude' => 0,
        'label' => 'Summary',
        'config' => array (
            'type' => 'text',
            'cols' => 50,
            'rows' => 2
        )
    ),
    'tx_gowestconfig_hide_in_breakpoint' => array (
        'exclude' => 1,
        'label' => 'Hide in Breakpoints',
        'config' => array (
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'multiple' => '0',
            'items' => array(
                array(
                    'xs - Breakpoint',
                    'xs',
                ),
                array(
                    'sm - Breakpoint',
                    'sm',
                ),
                array(
                    'md - Breakpoint',
                    'md',
                ),
                array(
                    'lg - Breakpoint',
                    'lg',
                ),
            ),
            'size' => '4',
            'autosizemax' => '4',
            'maxitems' => '4',
            'minitems' => '0'
        ),
    ),
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    $ttcontentColumn
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'frames',
    'tx_gowestconfig_gutter',
    'after:space_after_class'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'headers',
    'tx_gowestconfig_summary,--linebreak--',
    'before:header'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'access',
    '--linebreak--,tx_gowestconfig_hide_in_breakpoint,--linebreak--',
    'before:fe_group'
);

?>