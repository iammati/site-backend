<?php

defined('TYPO3_MODE') || die('Access denied.');

use Site\Core\Form\Fields\File;
use Site\Core\Form\Fields\Input;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Page-Tree -> Edit a page -> Resources tab -> select an item of the Sidebyside-Select in the Page TSConfig palette
ExtensionManagementUtility::registerPageTSConfigFile(
    getenv('BACKEND_EXT'),
    'Configuration/TSConfig/Main.ts',
    'Site - Backend'
);

ExtensionManagementUtility::addTCAcolumns('pages', [
    // Header
    'logo' => File::make('Logo', [
        'fieldName' => 'logo',
    ]),

    // Footer
    'copyright' => Input::make('Footer Copyright-Text'),
]);

ExtensionManagementUtility::addToAllTCAtypes(
    'pages',

    '--div--;Header,
        logo,
    --div--;Footer,
        copyright,
    '
);
