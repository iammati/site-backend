<?php

declare(strict_types=1);

namespace Site\SiteBackend\Configuration\Listener;

use Site\Core\Configuration\Event\AfterCeDefaultTcaRetrievedEvent;
use TYPO3\CMS\Core\Http\Dispatcher;

class AfterCeDefaultTcaRetrievedListener
{
    protected Dispatcher $dispatcher;

    public function __invoke(AfterCeDefaultTcaRetrievedEvent $event)
    {
        $identifier = 'Backend.ContentElements:'.getCeByCtype($event->getCType(), false);
        $label = ll(env('BACKEND_EXT'), $identifier)['title'] ?? $identifier;

        $event->setTabName(
            "{$label}"
        );
    }
}
