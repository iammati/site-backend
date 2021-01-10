<?php

declare(strict_types=1);

namespace Site\Backend\Event;

use Site\Backend\Interfaces\CTypeRenderingInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class RteRenderingEvent implements CTypeRenderingInterface
{
    /**
     * Listener which can manipulate then data of the $cObj
     * itself then as a custom DataProcessing as TypoScript would do.
     * 
     * @param ContentObjectRenderer $cObj
     * 
     * @return ContentObjectRenderer
     */
    public function listener(ContentObjectRenderer &$cObj)
    {
        // $cObj->renderedView = 'YA KELB';

        return $cObj;
    }
}
