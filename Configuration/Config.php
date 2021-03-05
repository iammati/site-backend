<?php

return [
    'localizationType' => 'custom',

    'IRREs' => [
        'prefix' => 'tx_'.env('CUSTOMER_PROJECT').'_',
    ],

    'Backend' => [
        'Preview' => [
            'enabled' => false,
            'extKey' => 'site_backend',
            'templateRootPaths' => 'Resources/Private/Backend/Templates',
        ],
    ],

    'Fields' => [
        'Container' => [
            'spacers' => [
                ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', ''],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_small', 'extra-small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_small', 'small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_medium', 'medium'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_large', 'large'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_large', 'extra-large'],
            ],
        ],
    ],
];
