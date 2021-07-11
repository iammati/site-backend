<?php

declare(strict_types=1);

use Site\Core\Form\Fields;
use Site\Core\Helper\ConfigHelper;
use Site\Core\Service\TcaService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Container-Records
$txContainerRecordsColPosFields = [
    10, 20, 30, 40
];

foreach ($txContainerRecordsColPosFields as $key => $colPos) {
    unset($txContainerRecordsColPosFields[$key]);

    $txContainerRecordsColPosFields['tx_container_records_colpos_'.$colPos] = [
        'label' => 'Column-'.$colPos.' records',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tt_content',
            'foreign_field' => 'tx_container_parent',
            'foreign_match_fields' => [
                'colPos' => $colPos
            ],
            'appearance' => [
                'showSynchronizationLink' => true,
                'showAllLocalizationLink' => true,
                'showPossibleLocalizationRecords' => true
            ]
        ]
    ];
}

// Default fields of this application for the 'tt_content' table
ExtensionManagementUtility::addTCAcolumns('tt_content', array_merge([
    'containerIsFluid' => Fields\Check::make('Container-Fluid', [
        'config' => [
            'renderType' => 'checkboxToggle',
        ],
    ]),

    'containerSpaceBefore' => Fields\Select::make('Space Before', [
        'config' => [
            'items' => ConfigHelper::get(env('BACKEND_EXT'), 'Fields.Container.spacers'),
        ],
    ]),

    'containerSpaceAfter' => Fields\Select::make('Space After', [
        'config' => [
            'items' => ConfigHelper::get(env('BACKEND_EXT'), 'Fields.Container.spacers'),
        ],
    ]),

    'fd_header' => Fields\Input::make('Header'),
    'fd_colorpicker' => Fields\Input\Colorpicker::make('Colorpicker'),
    'fd_rte' => Fields\RTE::make('RTE'),

    'fd_file' => Fields\File::make('File', [
        'fieldName' => 'fd_file',
    ]),
], $txContainerRecordsColPosFields));

// Registers automatically all irre_*_item configuration for CTRL-configured IRRE items.
// Take a look at the method's code to easier understand what it does in detail.
TcaService::registerIRREs(__DIR__.'/..', ConfigHelper::get(env('BACKEND_EXT'), 'IRREs.prefix'));

// Loads all in this __DIR__ configured CTypes starting with 'ce_'.
// For more read the brief-description or the code of this static method.
TcaService::loadCEs(__DIR__);
