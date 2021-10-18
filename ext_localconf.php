<?php

use Site\SiteBackend\Hooks\DatamapBeforeStartHook;
use Site\SiteBackend\Service\LocalconfService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

(function () {
    include ExtensionManagementUtility::extPath('site_backend', 'helpers.php');

    /** @var LocalconfService */
    $localconfService = GeneralUtility::makeInstance(LocalconfService::class);
    $localconfService->register();

    // Example allowed file extensions
    // $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['field_svgs'] = 'svg';
    // $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['svgicon'] = 'svg';

    // Realworld example
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['field_file'] = 'svg';

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_backend']['TCA']['txContainerRecordsColPos'] = [
        'positions' => [10, 20, 30, 40],
        'prefix' => 'tx_container_records_colpos_',
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['tx_container-before-start'] = DatamapBeforeStartHook::class;
})();
