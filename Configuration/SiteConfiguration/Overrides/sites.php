<?php

/**
 * Sites Configuration.
 *
 * URI Handling
 */
\Site\Core\Service\TCAService::addSiteConfigurationTCA(
    // Tab name
    'URI Handling',

    // Column field configurations
    [
        // Redirect on uppercase
        'site_redirectOnUpperCase' => [
            'Check',
            'Redirect on uppercase?',

            [
                'renderType' => 'checkboxToggle',
            ],
        ],
    ]
);
