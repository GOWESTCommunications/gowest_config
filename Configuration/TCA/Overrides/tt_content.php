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
    'tx_gowestconfig_crop' => array(
        'exclude' => 0,
        'label'   => 'Cropping',
        'onChange' => 'reload',
                    'config'  => array(
            'type'  => 'check',
            'items' => array (
                '1' => array(
                    '0' => 'LLL:EXT:lang/locallang_core.xml:labels.enabled',
                ),
            ),
        ),
    ),
    'tx_gowestconfig_aspect_ratio' => array (
        'exclude' => 0,
        'displayCond' => 'FIELD:tx_gowestconfig_crop:REQ:true',
        'label'   => 'Aspect ratio',
        'config'  => array (
            'type'  => 'select',
            'renderType' => 'selectSingle',
            'items' => array (
                array('default', 	'default', 		''),
                array('2:1', 	'2:1', 		''),
                array('1:1', 	'1:1', 		''),
                array('3:2',    '3:2',  	''),
                array('4:3', 	'4:3', 		''),
                array('5:3',    '5:3',  	''),
                array('8:5',    '8:5',  	''),
                array('14:9',   '14:9', 	''),
                array('16:9', 	'16:9', 	''),
                array('64:27',  '64:27',	'')
            ),
            'size'          => 1,
            'selicon_cols'  => 6,
            'showIconTable' => 6,
            'maxitems'      => 1,
            'default'       => 'default',
        )
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