<?php

defined('TYPO3_MODE') || die();

// Template-Module -> Info/Modify -> Edit the whole (...) -> Includes tab -> add from Available Items
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    env('BACKEND_EXT'),
    'Configuration/TypoScript',
    'Site - Backend'
);
