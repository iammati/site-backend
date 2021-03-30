<?php

declare(strict_types=1);

use Site\Core\Form\Fields;
use Site\Core\Helper\ConfigHelper;
use Site\Core\Service\TCAService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Adding a new Item Group to the TCA Select Item CEs
TCAService::addItemGroup('CustomElements', 'Custom Elements');

// Default fields of this application for the 'tt_content' table
ExtensionManagementUtility::addTCAcolumns('tt_content', [
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

    'ce_header' => Fields\Input::make('Header'),
    'ce_colorpicker' => Fields\Input\Colorpicker::make('Colorpicker'),
    'ce_rte' => Fields\RTE::make('RTE'),

    'ce_file' => Fields\File::make('File', [
        'fieldName' => 'ce_file',
    ]),
]);

// Registers automatically all irre_*_item configuration for CTRL-configured IRRE items.
// Take a look at the method's code to easier understand what it does in detail.
TCAService::registerIRREs(__DIR__.'/..', ConfigHelper::get(env('BACKEND_EXT'), 'IRREs.prefix'));

// Loads all in this __DIR__ configured CTypes starting with 'ce_'.
// For more read the brief-description or the code of this static method.
TCAService::loadCEs(__DIR__);
