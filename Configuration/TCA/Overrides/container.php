<?php

/** @var Site\SiteBackend\Service\ContainerService */

use Site\Core\Service\TcaService;

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

TcaService::showFields(basename(__FILE__, '.php'), '
    --palette--;;txContainerRecords1Col,
');
