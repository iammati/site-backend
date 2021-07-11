<?php

use Site\Core\Service\TcaService;

(function () {
    TcaService::allowTablesStartsWith('tx_'.str_replace('_', '', env('BACKEND_EXT')));

    if (TYPO3_MODE === 'BE') {
        // Save and close Button as in previous TYPO3 versions provided
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = Site\Core\Hook\SaveCloseHook::class.'->addButton';

        // Optionally disable CTRL+S save-shortcut in backend user settings.
        // By default it's enabled (true).
        $GLOBALS['TYPO3_USER_SETTINGS']['columns']['disableSaveShortcut'] = [
            'type' => 'check',
            'label' => 'LLL:EXT:'.env('CORE_EXT').'/Resources/Private/Language/locallang.xml:userSettings.disableSaveShortcut',
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToUserSettings(
            'disableSaveShortcut',
            'before:resetConfiguration'
        );
    }
})();
