<?php

/**
 * @var Site\SiteBackend\Service\ContainerService
 *
 * Registers a container-configuration
 */
$containerService = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Site\SiteBackend\Service\ContainerService::class);
$containerService->register([
    'ctype' => 'container',
    'label' => 'Container',
    'description' => 'The default container',

    'definitions' => [
        [
            ['name' => 'Column', 'colPos' => 10],
        ],
    ],

    'additionalFields' => [
        '--palette--;;containerDefault',
    ],
]);
