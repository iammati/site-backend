<?php

(function () {
    include TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('site_backend', 'helpers.php');

    /** @var \Site\SiteBackend\Service\LocalconfService */
    $localconfService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Site\SiteBackend\Service\LocalconfService::class);
    $localconfService->register();

    // Example allowed file extensions
    // $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['field_svgs'] = 'svg';
    // $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['svgicon'] = 'svg';

    // Realworld example
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['field_file'] = 'svg';
})();
