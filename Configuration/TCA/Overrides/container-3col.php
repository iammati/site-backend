<?php

use Site\Core\Service\TcaService;
use Site\SiteBackend\Service\ContainerService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var ContainerService */
$containerService = GeneralUtility::makeInstance(ContainerService::class);

$containerService->register([
    'ctype' => 'container-3col',
    'label' => 'Container 3-Columns',
    'description' => '3-Columns Container',

    'definitions' => [
        [
            ['name' => 'Left-Column', 'colPos' => 10],
            ['name' => 'Center-Column', 'colPos' => 20],
            ['name' => 'Right-Column', 'colPos' => 30],
        ],
    ],

    'additionalFields' => [
        '--palette--;;containerDefault',
    ],
]);

TcaService::showFields(basename(__FILE__, '.php'), '
    --palette--;;txContainerRecords3Col,
');
