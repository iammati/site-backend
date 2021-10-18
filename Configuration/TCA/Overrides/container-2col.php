<?php

use Site\Core\Service\TcaService;
use Site\SiteBackend\Service\ContainerService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var ContainerService */
$containerService = GeneralUtility::makeInstance(ContainerService::class);

$containerService->register([
    'ctype' => 'container-2col',
    'label' => 'Container 2-Columns',
    'description' => '2-Columns Container',

    'definitions' => [
        [
            ['name' => 'Left-Column', 'colPos' => 10],
            ['name' => 'Right-Column', 'colPos' => 20],
        ],
    ],

    'additionalFields' => [
        '--palette--;;containerDefault',
    ],
]);

TcaService::showFields(basename(__FILE__, '.php'), '
    --palette--;;txContainerRecords2Col,
');
