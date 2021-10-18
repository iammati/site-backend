<?php

use Site\Core\Service\TcaService;
use Site\SiteBackend\Service\ContainerService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var ContainerService */
$containerService = GeneralUtility::makeInstance(ContainerService::class);

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
