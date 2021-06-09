<?php

/**
 * Extension Manager/Repository config file for ext "gowest_config".
 */
$EM_CONF['gowest_config'] = [
    'title' => 'GO.WEST Config',
    'description' => 'GO.WEST Default Config',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99',
            'fluid_styled_content' => '9.5.0-10.4.99',
            'rte_ckeditor' => '9.5.0-10.4.99'
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Gowest\\GowestConfig\\' => 'Classes'
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Andreas Lochner',
    'author_email' => 'a.lochner@go-west.at',
    'author_company' => 'GO.WEST',
    'version' => '1.0.2',
];
