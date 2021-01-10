<?php

declare(strict_types=1);

namespace Site\Backend\Service;

use Site\Backend\ToolbarItem\ClearCacheToolbarItem;
use Site\Core\Service\LocalizationService;
use Site\Core\Service\RTEService;
use Site\Core\Service\TCAService;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LocalconfService
{
    /**
     * @var string
     */
    protected $backendExtKey = null;

    /**
     * @var LocalizationService
     */
    protected $localizationService = null;

    public function __construct()
    {
        $this->localizationService = GeneralUtility::makeInstance(LocalizationService::class);
    }

    /**
     * The public-accesible of the registration for the backend of this TYPO3 application.
     *
     * @return void
     */
    public function register()
    {
        $this->backendExtKey = getenv('BACKEND_EXT');

        if ($this->backendExtKey !== false) {
            // $this->authBackend();
            $this->rteRegistration();
            $this->iconRegistration();

            $this->localizationService->register($this->backendExtKey, [
                'default' => 'Resources/Private/Language/',
            ]);
        }
    }

    /**
     * An automated-authentication for the TYPO3 backend.
     * Only affects if the Environment-Context is in development - Staging / Live will never be affected by that.
     *
     * @return void
     */
    protected function authBackend()
    {
        // Only in Development-Context an automatically auth service will be used for an auto-login of the TYPO3 backend
        if (Environment::getContext()->isDevelopment()) {
            $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1] = ClearCacheToolbarItem::class;

            if ((TYPO3_REQUESTTYPE && TYPO3_REQUESTTYPE_BE)) {
                ExtensionManagementUtility::addService(
                    $this->backendExtKey,
                    'auth',

                    AutoAuthService::class,

                    [
                        'title' => 'Auto User authentication',
                        'description' => 'Auto authenticate user with configured username',
                        'subtype' => 'authUserBE, getUserBE',
                        'available' => true,
                        'priority' => 100,
                        'quality' => 50,
                        'className' => AutoAuthService::class,
                    ]
                );

                $GLOBALS['TYPO3_CONF_VARS']['SVCONF']['auth']['setup']['BE_alwaysFetchUser'] = true;
                $GLOBALS['TYPO3_CONF_VARS']['SVCONF']['auth']['setup']['BE_alwaysAuthUser'] = true;
            }
        }
    }

    /**
     * Registers the default RTE YAML-configuration file - only in 'BE' mode.
     *
     * @return void
     */
    protected function rteRegistration()
    {
        if (TYPO3_REQUESTTYPE && TYPO3_REQUESTTYPE_BE && TYPO3_MODE === 'BE') {
            // Registration of the default RTE configuration for this application.
            RTEService::register(
                $this->backendExtKey,
                'Default'
            );
        }
    }

    /**
     * Registers the Icons for all custom-elements.
     * E.g. an icon-identifier looks like: <company>-backend-ce-accordions.
     *
     * @return void
     */
    protected function iconRegistration()
    {
        TCAService::registerCEIcons(
            ExtensionManagementUtility::extPath($this->backendExtKey, 'Configuration/TCA/Overrides'),
            $this->backendExtKey
        );
    }
}
