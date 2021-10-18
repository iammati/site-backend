<?php

declare(strict_types=1);

namespace Site\SiteBackend\Service;

use Site\Core\Service\LocalizationService;
use Site\Core\Service\RTEService;
use Site\Core\Service\TcaService;
use Site\SiteBackend\ToolbarItem\ClearCacheToolbarItem;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Site\Core\Service\ModelService;
use Site\Core\Helper\ConfigHelper;
use Site\Core\Service\FormEngineService;
use Site\SiteBackend\Form\Element\ContainerRecordsElement;

class LocalconfService
{
    protected string $backendExtKey;
    protected LocalizationService $localizationService;

    public function __construct()
    {
        $this->localizationService = GeneralUtility::makeInstance(LocalizationService::class);
    }

    /**
     * The public-accesible of the registration for the backend of this TYPO3 application.
     */
    public function register(): void
    {
        $this->backendExtKey = env('BACKEND_EXT');

        if ($this->backendExtKey !== false) {
            $this
                // Automatically authentication on Development Application-/EnvironmentContext
                // ->authBackend()

                // YAML registration for the CKEditor (RTE)
                ->rteRegistration()

                // Icons for custom ContentElements (newWizardElement)
                ->iconRegistration()

                // Custom FormEngine-Fields
                ->nodeRegistration()

                // Models
                ->modelRegistration()

                // Localization
                ->localizationService->register($this->backendExtKey, [
                    'default' => 'Resources/Private/Language/',
                ])
            ;
        }
    }

    /**
     * An automated-authentication for the TYPO3 backend.
     * Only affects if the Environment-Context is in development - Staging / Live will never be affected by that.
     */
    protected function authBackend(): self
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

        return $this;
    }

    /**
     * Registers the default RTE YAML-configuration file - only in 'BE' mode.
     */
    protected function rteRegistration(): self
    {
        if (TYPO3_REQUESTTYPE && TYPO3_REQUESTTYPE_BE && TYPO3_MODE === 'BE') {
            // Registration of the default RTE configuration for this application.
            RTEService::register(
                $this->backendExtKey,
                'Default'
            );
        }

        return $this;
    }

    /**
     * Registers the Icons for all custom-elements.
     * E.g. an icon-identifier looks like: <company>-backend-ce-accordions.
     */
    protected function iconRegistration(): self
    {
        TcaService::registerCEIcons(
            ExtensionManagementUtility::extPath($this->backendExtKey, 'Configuration/TCA/Overrides'),
            $this->backendExtKey
        );

        return $this;
    }

    /**
     * Registration of the custom FormEngineElement 'IsIrreElement'
     * to know when an IRRE is rendered or not for further handling in
     * the EventRendering of a ContentObjectRenderer.
     */
    protected function nodeRegistration(): self
    {
        FormEngineService::register(
            'containerRecords',
            40,
            ContainerRecordsElement::class,
            1612899715
        );

        return $this;
    }

    protected function modelRegistration(): self
    {
        ModelService::generate(
            'site_backend',
            'Site\\SiteBackend\\Domain\\Model',
            'Ttcontent',
            ConfigHelper::get(env('BACKEND_EXT'), 'Fields.Ttcontent') ?? []
        );

        ModelService::generate(
            'site_backend',
            'Site\\SiteBackend\\Domain\\Model',
            'Accordions',
            [
                'string' => [
                    'header' => "''",
                    'rte' => "''",
                ],
            ]
        );

        return $this;
    }
}
