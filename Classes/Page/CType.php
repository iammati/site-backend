<?php

declare(strict_types=1);

namespace Site\Backend\Page;

use Site\Core\Utility\StandaloneViewUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class CType
{
    /**
     * @var ContentObjectRenderer
     */
    public $cObj;

    /**
     * @var string
     */
    public $renderedView;

    /**
     * @return void
     */
    public function rendering()
    {
        $data = $this->cObj->data;
        $CType = $data['CType'];

        $templateCType = getCeByCtype($CType);

        $this->cObj->renderedView = StandaloneViewUtility::render(
            [
                'Templates' => 'EXT:'.getenv('FRONTEND_EXT').'/Resources/Private/Fluid/Content/Templates/',
                'Partials' => 'EXT:'.getenv('FRONTEND_EXT'). '/Resources/Private/Fluid/Content/Partials/',
                'Layouts' => 'EXT:'.getenv('FRONTEND_EXT'). '/Resources/Private/Fluid/Content/Layouts/',
            ],

            $templateCType.'.html',
            
            [
                'data' => $data
            ],
        );

        $eventNamespace = 'Site\\Backend\\Event\\'.$templateCType. 'RenderingEvent';
        if (class_exists($eventNamespace)) {
            $eventClass = GeneralUtility::makeInstance($eventNamespace);
            $this->cObj = $eventClass->listener($this->cObj);
        }

        return trim($this->cObj->renderedView);
    }
}
