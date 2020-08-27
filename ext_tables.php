<?php
defined('TYPO3_MODE') || die();
$GLOBALS['TYPO3_CONF_VARS']['GFX']['colorspace'] = 'sRGB';


$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'] = ['q'];
$GLOBALS['TYPO3_CONF_VARS']['FE']['additionalCanonicalizedUrlParameters'] = ['q'];


$GLOBALS['TCA']['tt_content']['columns']['sectionIndex']['config']['default'] = 0;

//Mehrzeilige Überschriften
$GLOBALS['TCA']['pages']['columns']['title']['config']['type'] = 'text';
$GLOBALS['TCA']['pages']['columns']['title']['config']['rows'] = '3';

$GLOBALS['TCA']['pages']['columns']['subtitle']['config']['type'] = 'text';
$GLOBALS['TCA']['pages']['columns']['subtitle']['config']['rows'] = '3';

$GLOBALS['TCA']['pages']['columns']['slug']['config']['generatorOptions']['fields'] = ['nav_title,title'];
$GLOBALS['TCA']['pages']['columns']['slug']['config']['generatorOptions']['replacements'] = [
    //'smart' => 'test',
];



$GLOBALS['TCA']['tt_content']['columns']['header']['config']['type'] = 'text';
$GLOBALS['TCA']['tt_content']['columns']['header']['config']['rows'] = '3';
$GLOBALS['TCA']['tt_content']['columns']['header']['config']['cols'] = '50';

$GLOBALS['TCA']['tt_content']['columns']['subheader']['config']['type'] = 'text';
$GLOBALS['TCA']['tt_content']['columns']['subheader']['config']['rows'] = '2';
$GLOBALS['TCA']['tt_content']['columns']['subheader']['config']['cols'] = '50';

$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants'] = [
    'xs' => [
        'title' => 'XS',
        'allowedAspectRatios' => [

            'NaN'    => [
                'title' => 'Free',
                'value' => 0.0,
            ],

            '16:9'    => [
                'title' => '16:9',
                'value' => 16 / 9,
            ],

            '4:3'    => [
                'title' => '4:3',
                'value' => 4 / 3,
            ],

            '3:4'    => [
                'title' => '3:4',
                'value' => 3 / 4,
            ],

            '3:2'    => [
                'title' => '3:2',
                'value' => 3 / 2,
            ],

            '3:1'    => [
                'title' => '3:1',
                'value' => 3 / 1,
            ],

            '2:3'    => [
                'title' => '2:3',
                'value' => 2 / 3,
            ],

            '2:1'    => [
                'title' => '2:1',
                'value' => 2 / 1,
            ],

            '1:2'    => [
                'title' => '1:2',
                'value' => 1 / 2,
            ],

            '1:1'    => [
                'title' => '1:1',
                'value' => 1 / 1,
            ],

        ],
    ],
];
$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['sm'] = $GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['xs'];
$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['sm']['title'] = 'SM';
$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['md'] = $GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['xs'];
$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['md']['title'] = 'MD';
$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['lg'] = $GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['xs'];
$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants']['lg']['title'] = 'LG';

/*
$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['ratios'] = [
    'NaN'    => [
        'title' => 'Free',
        'value' => 0.0,
    ],
    
    '16:9'    => [
        'title' => '16:9',
        'value' => 16 / 9,
    ],
    
    '4:3'    => [
        'title' => '4:3',
        'value' => 4 / 3,
    ],
    
    '3:4'    => [
        'title' => '3:4',
        'value' => 3 / 4,
    ],
    
    '3:2'    => [
        'title' => '3:2',
        'value' => 3 / 2,
    ],
    
    '3:1'    => [
        'title' => '3:1',
        'value' => 3 / 1,
    ],
    
    '2:3'    => [
        'title' => '2:3',
        'value' => 2 / 3,
    ],
    
    '2:1'    => [
        'title' => '2:1',
        'value' => 2 / 1,
    ],
    
    '1:2'    => [
        'title' => '1:2',
        'value' => 1 / 2,
    ],
    
    '1:1'    => [
        'title' => '1:1',
        'value' => 1 / 1,
    ],
];
*/

$GLOBALS['TCA']['tt_content']['columns']['colPos']['config']['items'] = [
    '0' => ['normal', '0'],
    '1' => ['left', '1'],
    '2' => ['right', '2'],
    '3' => ['border', '3'],
    '50' => ['media', '50'],
    '90' => ['inherited', '90'],
    '120' => ['landigpage', '120']
];
