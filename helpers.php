<?php

if (!function_exists('getCeByCtype')) {
    /**
     * Generates an identifier by the provided $CType-string.
     * Turns a 'ce_rte' to 'Rte' - optional also definable whether upper- or lowercase.
     * 
     * @param string $CType The CType of the content-element. Could be e.g. 'ce_rte'.
     * @param string $upperCase Optional. Default true - determines whether the first letter should be upperCase or not.
     * 
     * @return string
     */
    function getCeByCtype($CType, $upperCase = true)
    {
        $CType = str_replace('ce_', '', $CType);

        return ($upperCase ? ucfirst($CType) : lcfirst($CType));
    }
}

// if (!function_exists('getStandaloneView')) {
//     /**
//      * Returns a new standalone view - shorthand function.
//      * 
//      * @param string $extKey Key of the extension e.g. 'site_backend'.
//      * @param array $paths A set of rootPaths with the following scheme:
//      * - $paths => ['templates' => '/Resources/Private/Templates/Fluid/Content/', 'partials' => '(...)']
//      *
//      * @return TYPO3\CMS\Fluid\View\StandaloneView
//      * 
//      * @throws \InvalidArgumentException
//      * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException
//      */
//     function getStandaloneView($extKey, $paths)
//     {
//         /** @var TYPO3\CMS\Fluid\View\StandaloneView $view */
//         $view = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Fluid\View\StandaloneView::class);

//         if (isset($paths['templates'])) {
//             $view->setTemplateRootPaths([TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT')])
//         }

//         $view->setLayoutRootPaths([TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Layouts')]);
//         $view->setPartialRootPaths([TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Partials')]);
//         $view->setTemplateRootPaths([TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Templates')]);

//         $view->setTemplatePathAndFilename(TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Templates/Clipboard/Main.html'));

//         // $view->getRequest()->setControllerExtensionName($extName);

//         return $view;
//     }
// }
