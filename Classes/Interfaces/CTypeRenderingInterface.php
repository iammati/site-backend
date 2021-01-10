<?php

declare(strict_types=1);

namespace Site\Backend\Interfaces;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

interface CTypeRenderingInterface
{
    /**
     * Listener which can manipulate then data of the $cObj
     * itself then as a custom DataProcessing as TypoScript would do.
     * 
     * @param ContentObjectRenderer $cObj
     * 
     * @return ContentObjectRenderer
     */
    public function listener(ContentObjectRenderer &$cObj);
}
