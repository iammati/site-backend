<?php

use Site\Core\Service\TcaService;
use Site\SiteBackend\Service\ContainerService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var ContainerService */
$containerService = GeneralUtility::makeInstance(ContainerService::class);

$containerService->register([
    'ctype' => 'container-4col',
    'label' => 'Container 4-Columns',
    'description' => '4-Columns Container',

    'definitions' => [
        [
            ['name' => 'Left-Column', 'colPos' => 10],
            ['name' => 'Centered Left-Column', 'colPos' => 20],
            ['name' => 'Centered Right-Column', 'colPos' => 30],
            ['name' => 'Right-Column', 'colPos' => 40],
        ],
    ],

    'additionalFields' => [
        '--palette--;;containerDefault',
    ],
]);

TcaService::showFields(basename(__FILE__, '.php'), '
    --palette--;;txContainerRecords4Col,
');
