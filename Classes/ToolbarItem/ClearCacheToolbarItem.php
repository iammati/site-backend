<?php

namespace Site\Backend\ToolbarItem;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Custom Cache Toolbar Item which mainly flushes all caches (frontend and system).
 */
class ClearCacheToolbarItem implements ToolbarItemInterface
{
    /**
     * @var array
     */
    protected $cacheActions = [];

    /**
     * @var array
     */
    protected $optionValues = [];

    /**
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Toolbar/ClearCacheMenu');
        $isAdmin = $this->getBackendUser()->isAdmin();
        $userTsConfig = $this->getBackendUser()->getTSConfig();
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

        // Clear all page-related caches
        // if ($isAdmin || $userTsConfig['options.']['clearCache.']['pages'] ?? false) {
        //     $this->cacheActions[] = [
        //         'id' => 'pages',
        //         'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:flushPageCachesTitle',
        //         'description' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:flushPageCachesDescription',
        //         'href' => (string)$uriBuilder->buildUriFromRoute('tce_db', ['cacheCmd' => 'pages']),
        //         'iconIdentifier' => 'actions-system-cache-clear-impact-low'
        //     ];
        //     $this->optionValues[] = 'pages';
        // }

        // Clearing of all caches is only shown if explicitly enabled via TSConfig
        // or if BE-User is admin and the TSconfig explicitly disables the possibility for admins.
        // This is useful for big production systems where admins accidentally could slow down the system.
        if ($userTsConfig['options.']['clearCache.']['all'] ?? false
            || ($isAdmin && (bool) ($userTsConfig['options.']['clearCache.']['all'] ?? true))
        ) {
            $this->cacheActions[] = [
                'id' => 'all',
                'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:flushAllCachesTitle2',
                'description' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:flushAllCachesDescription2',
                'href' => (string) $uriBuilder->buildUriFromRoute('tce_db', ['cacheCmd' => 'all']),
                'iconIdentifier' => 'actions-system-cache-clear-impact-high',
            ];
            $this->optionValues[] = 'all';
        }

        // Hook for manipulating cacheActions
        foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'] ?? [] as $cacheAction) {
            $hookObject = GeneralUtility::makeInstance($cacheAction);
            if (!$hookObject instanceof ClearCacheActionsHookInterface) {
                throw new \UnexpectedValueException($cacheAction.' must implement interface '.ClearCacheActionsHookInterface::class, 1228262000);
            }
            $hookObject->manipulateCacheActions($this->cacheActions, $this->optionValues);
        }
    }

    /**
     * Checks whether the user has access to this toolbar item.
     *
     * @return bool TRUE if user has access, FALSE if not
     */
    public function checkAccess()
    {
        $backendUser = $this->getBackendUser();
        if ($backendUser->isAdmin()) {
            return true;
        }
        foreach ($this->optionValues as $value) {
            if ($backendUser->getTSConfig()['options.']['clearCache.'][$value] ?? false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render clear cache icon, based on the option if there is more than one icon or just one.
     *
     * @return string Icon HTML
     */
    public function getItem()
    {
        if ($this->hasDropDown()) {
            return $this->getFluidTemplateObject('ClearCacheToolbarItem.html')->render();
        }
        $view = $this->getFluidTemplateObject('ClearCacheToolbarItemSingle.html');
        $cacheAction = end($this->cacheActions);
        $view->assignMultiple([
                'link' => $cacheAction['href'],
                'title' => $cacheAction['title'],
                'iconIdentifier' => $cacheAction['iconIdentifier'],
            ]);

        return $view->render();
    }

    /**
     * Render drop down.
     *
     * @return string Drop down HTML
     */
    public function getDropDown()
    {
        $view = $this->getFluidTemplateObject('ClearCacheToolbarItemDropDown.html');
        $view->assign('cacheActions', $this->cacheActions);

        return $view->render();
    }

    /**
     * No additional attributes needed.
     *
     * @return array
     */
    public function getAdditionalAttributes()
    {
        return [];
    }

    /**
     * This item has a drop down if there is more than one cache action available for the current Backend user.
     *
     * @return bool
     */
    public function hasDropDown()
    {
        return count($this->cacheActions) > 1;
    }

    /**
     * Position relative to others.
     *
     * @return int
     */
    public function getIndex()
    {
        return 10;
    }

    /**
     * Returns the current BE user.
     *
     * @return BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * @return PageRenderer
     */
    protected function getPageRenderer()
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }

    /**
     * Returns a new standalone view, shorthand function.
     *
     * @param string $filename which templateFile should be used
     */
    protected function getFluidTemplateObject(string $filename): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);

        $extensionKey = env('BACKEND_EXT');

        $view->setLayoutRootPaths(['EXT:'.$extensionKey.'/Resources/Private/Layouts']);
        $view->setPartialRootPaths(['EXT:'.$extensionKey.'/Resources/Private/Partials/ToolbarItems']);
        $view->setTemplateRootPaths(['EXT:'.$extensionKey.'/Resources/Private/Templates/ToolbarItems']);

        $view->setTemplate($filename);

        $view->getRequest()->setControllerExtensionName('Backend');

        return $view;
    }
}
