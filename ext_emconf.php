<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Backend',
    'description' => 'Backend User-Interface Extension for TYPO3. Manage things like Caching or anything similiar here.',
    'category' => 'be',
    'author' => 'Mati',
    'author_email' => 'mati_01@icloud.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.1.1',

    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
            'site_core' => '1.5',
            'container' => '1.2',
        ],

        'conflicts' => [],
        'suggests' => [],
    ],
];
