<?php

defined('TYPO3_MODE') || die('Access denied.');

(function () {
    /** @var LocalconfService */
    $localconfService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Site\Backend\Service\LocalconfService::class);
    $localconfService->register();

    // Example allowed file extensions
    // $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['ce_svgs'] = 'svg';
    // $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['svgicon'] = 'svg';
})();
