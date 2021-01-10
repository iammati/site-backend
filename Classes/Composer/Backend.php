<?php

declare(strict_types=1);

namespace Site\Backend\Composer;

use Site\Core\Utility\CurlUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Checks for any new release to let you know during any composer-postAutoload
 * triggered event for a new release within a migration if required.
 */
class Backend
{
    /**
     * @var string
     */
    protected static $version = '1.0.0';

    /**
     * @var string
     */
    protected static $remoteUriVersion = 'https://raw.githubusercontent.com/iammati/site-backend/master/composer.json';

    /**
     * Retrives the current local version.
     * 
     * @return string
     */
    public static function getVersion()
    {
        return self::$version;
    }

    public static function checkVersion()
    {
        /** @var CurlUtility */
        $curlObj = GeneralUtility::makeInstance(CurlUtility::class);
        $curlObj->execute(self::$remoteUriVersion);

        $localVersion = self::getVersion();
        $remoteVersion = json_decode($curlObj->getResponse())->version;

        $whitespaceFunc = function (string $actualMessage, int $iterating = 0) {
            for ($i = 0; $i < $iterating; $i++) {
                echo "\n";
            }

            echo $actualMessage . "\n";

            for ($i = 0; $i < $iterating; $i++) {
                echo "\n";
            }
        };

        if ($localVersion != $remoteVersion) {
            $whitespaceFunc('🆕 EXT:site_backend is available as version ' . $remoteVersion . '. Your local version of ' . $localVersion . ' can be updated 🆕', 3);
        } else {
            $whitespaceFunc('🔥 EXT:site_backend runs the latest version ('.$localVersion.') 🔥', 1);
        }
    }
}
