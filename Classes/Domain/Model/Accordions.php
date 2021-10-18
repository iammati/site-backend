<?php

declare(strict_types=1);

namespace Site\SiteBackend\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Accordions extends AbstractEntity
{
    /** @var string */
    protected $header = '';
    
    /** @var string */
    protected $rte = '';
    
    public function setHeader(string $header)
    {
        $this->header = $header;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function setRte(string $rte)
    {
        $this->rte = $rte;
    }

    public function getRte(): string
    {
        return $this->rte;
    }
}
