<?php

declare(strict_types=1);

namespace Site\SiteBackend\Service;

use B13\Container\Tca\Registry;
use B13\Container\Tca\ContainerConfiguration;
use Site\Core\Utility\StrUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContainerService
{
    /**
     * @var int
     */
    public const PALETTE_APPEND = 0;

    /**
     * Current type of the passed variable for type (e.g. appending).
     * Default is 0 to always append and see any changes.
     *
     * @var int
     */
    protected $currentType = 0;

    /**
     * The default TCA for any registered container.
     *
     * @var array
     */
    protected $baseTca = [
        'container' => [
            'Container' => [
                '',
            ],
        ],

        'general' => [
            'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general' => [
                '--palette--;;general',
                'header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header.ALT.div_formlabel',
            ],
        ],

        'appearance' => [
            'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance' => [
                '--palette--;;frames',
                '--palette--;;appearanceLinks',
            ],
        ],

        'language' => [
            'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language' => [
                '--palette--;;language',
            ],
        ],

        'access' => [
            'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access' => [
                '--palette--;;hidden',
                '--palette--;;access',
            ],
        ],

        'categories' => [
            'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories' => [
                'categories',
            ],
        ],

        'notes' => [
            'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes' => [
                'rowDescription',
            ],
        ],

        'extended' => [
            'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended' => [
            ],
        ],
    ];

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * Making the registry ready for the DI.
     */
    public function __construct()
    {
        $this->registry = GeneralUtility::makeInstance(Registry::class);
    }

    /**
     * Determines if the current call is appending.
     *
     * @return bool
     */
    protected function isAppending()
    {
        return $this->PALETTE_APPEND == $this->currentType;
    }

    /**
     * Manipulates the TCA of a given container by its CType and a given palette name.
     * Everything else is handled by the given $type.
     *
     * @param int    $type
     * @param string $palette the name of the palette to be manipulated
     * @param string $ctype   targeted CType of the container
     */
    public function manipulatePaletteByCType($type, $palette, $ctype, $additionalTCA = '')
    {
        $TCA = $GLOBALS['TCA']['tt_content']['types'][$ctype] ?? null;

        if ($TCA === null) {
            return;
        }

        $this->currentType = $type;
        $TCA = $TCA['showitem'];

        $newTca = '';
        $baseTca = $this->baseTca;

        foreach ($baseTca as $paletteName => $paletteArray) {
            $builtTca = '--div--;'.key($paletteArray).',';

            foreach ($paletteArray as $i => $palette) {
                $builtTca .= implode(',', $palette).',';
            }

            $newTca .= $builtTca;
        }

        if (StrUtility::endsWith($newTca, ',')) {
            $newTca = mb_substr($newTca, 0, mb_strlen($newTca) - 1);
        }

        $GLOBALS['TCA']['tt_content']['types'][$ctype]['showitem'] = $newTca;
    }

    /**
     * Adds additional fields for the TCA fields of the default containers provided by EXT:container.
     */
    public function addFields(string $ctype, array $showFields = [], string $targetedPalette = 'container')
    {
        $baseTca = $this->baseTca;
        $builtTca = '';

        $baseTca[$targetedPalette][key($baseTca[$targetedPalette])] = array_merge(
            $baseTca[$targetedPalette][key($baseTca[$targetedPalette])],
            $showFields
        );

        $size = sizeof($baseTca);
        $i = 0;

        foreach ($baseTca as $name => $paletteFields) {
            $label = key($paletteFields);

            $builtTca .= '                --div--;'.$label.",\r\n                    ";

            foreach ($paletteFields as $field) {
                $builtTca .= implode(',
                    ', $field);
            }

            if ($i != ($size - 1)) {
                $builtTca .= ",\r\n";
                ++$i;
            }
        }

        $GLOBALS['TCA']['tt_content']['types'][$ctype]['showitem'] = $builtTca;
    }

    /**
     * Registers the provided configurations for the EXT:container.
     *
     * @param array $config
     */
    public function register($config)
    {
        // Default configuration for the containers
        $ctype = $config['ctype'];
        $label = $config['label'];
        $description = $config['description'];
        $definitions = $config['definitions'];
        $additionalFields = $config['additionalFields'];

        $containerConfiguration = new ContainerConfiguration(
            $ctype,
            $label,
            $description,
            $definitions
        );

        $this->registry->configureContainer($containerConfiguration);

        // Adding the additionalFields via ContainerService
        if (isset($additionalFields) && !empty($additionalFields)) {
            $this->addFields(
                $ctype,
                $additionalFields
            );
        }
    }
}
