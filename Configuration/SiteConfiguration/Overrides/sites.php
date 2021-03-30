<?php

use Site\Core\Service\TCAService;
use Site\Core\Form\Fields;

/*
 * Sites Configuration.
 *
 * URI Handling
 */
TCAService::addSiteConfigurationTCA(
    // Tab name
    'URI-Handling',

    // Column field configurations
    [
        // Redirect on uppercase
        'site_redirectOnUpperCase' => Fields\Check::make('Redirect on upperCase?', [
            'description' => 'Redirects slugs, say like "/proDUCTS" to "/products" - resolves by turning all slugs to lowercase.',
            'config' => [
                'renderType' => 'checkboxToggle',
            ],
        ]),
    ]
);
