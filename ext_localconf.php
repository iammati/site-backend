<?php

(function () {
    include TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('site_backend', 'helpers.php');

    /** @var LocalconfService */
    $localconfService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Site\Backend\Service\LocalconfService::class);
    $localconfService->register();

    // Example allowed file extensions
    // $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['ce_svgs'] = 'svg';
    // $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['fileExtensions']['svgicon'] = 'svg';

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '
# Content element rendering
tt_content.default >
tt_content {
    key {
        field = CType
    }
    default = USER_INT
    default {
        userFunc = Site\Backend\Page\CType->rendering
    }
}
    '
    );
})();
