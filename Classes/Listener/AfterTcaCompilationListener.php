<?php

namespace Site\Backend\Listener;

use Site\Core\Helper\ConfigHelper;
use Site\Core\Service\TCAService;
use Site\Core\Utility\FileUtility;
use Site\Core\Utility\StrUtility;
use Symfony\Component\Finder\Finder;
use TYPO3\CMS\Core\Configuration\Event\AfterTcaCompilationEvent;
use TYPO3\CMS\Core\Http\Dispatcher;

class AfterTcaCompilationListener
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    public function callback(AfterTcaCompilationEvent $event)
    {
        $event->setTca(
            $this->update($event->getTca(), $event->getPosition())
        );
    }

    public function update($tca, $position)
    {
        $loadedIRREs = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_core']['TCA_SERVICE']['loadedIRREs'];
        $contentElements = TCAService::fetchCEs(__DIR__ . '/../../Configuration/TCA/Overrides/', false);

        $name = ConfigHelper::get(env('BACKEND_EXT'), 'IRREs.prefix');

        if (!StrUtility::endsWith($name, '_')) {
            $name .= '_';
        }

        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../Configuration/TCA/')->name($name . '*.php');

        $irreFiles = FileUtility::retrieveFilesByPath(__DIR__.'/../../Configuration/TCA/')->name($name . '*.php');
        $ceFiles = FileUtility::retrieveFilesByPath(__DIR__.'/../../Configuration/TCA/Overrides/')->name('*.php');

        foreach ($irreFiles as $file) {
            $fileName = basename($file->getRelativePathname(), '.php');

            if (in_array($fileName, $loadedIRREs)) {
                // $tca[$fileName]['ctrl']['label'] = '';
            }
        }

        foreach ($ceFiles as $file) {
            $fileName = basename($file->getRelativePathname(), '.php');

            if (in_array($fileName, $contentElements)) {
                $items = $tca['tt_content']['columns']['CType']['config']['items'];

                foreach ($items as $key => $item) {
                    $label = $item[0];
                    $CType = $item[1];
                    $iconIdentifier = $item[2];
                    $itemGroup = $item[3];

                    if ($CType == $fileName) {
                        $identifier = 'Backend.ContentElements:'.getCeByCtype($CType, false);
                        $label = ll(env('BACKEND_EXT'), $identifier)['title'] ?? $identifier;

                        $items[$key][0] = $label;
                    }
                }

                $tca['tt_content']['columns']['CType']['config']['items'] = $items;
            }
        }

        if ($position == 'afterParsed') {
            $tca = $this->afterParsed($tca);
        }

        return $tca;
    }

    public function afterParsed($cachedTca)
    {
        $tca = $this->update($cachedTca, 'default');

        return $tca;
    }
}
