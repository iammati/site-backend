<?php

namespace Site\Backend\Listener;

use TYPO3\CMS\Core\Configuration\Event\AfterTcaCompilationEvent;
use TYPO3\CMS\Core\Http\Dispatcher;

class AfterTcaCompilationListener
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        dd($this->dispatcher);
    }
	
    public function __invoke(AfterTcaCompilationEvent $event): void
    {
        throw new \Exception("222OK");
    }

    public function callback()
    {
        throw new \Exception("OK");
    }
}
