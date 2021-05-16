<?php

declare(strict_types=1);

namespace Site\SiteBackend\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * This file is part of the 'site_backend' extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *
 * @author Mati Sediqi <mati_01@icloud.com>
 * @copyright 2021 MIT
 **/
class Ttcontent extends AbstractEntity
{
    /**
     * @var string
     */
    protected $CType = '';

    /**
     * @var string
     */
    protected $header = '';

    /**
     * @var string
     */
    protected $subheader = '';

    /**
     * @var string
     */
    protected $colPos = '';

    /**
     * @var string
     */
    protected $parentId = '';
    
    /**
     * @var string
     */
    protected $parentTable = '';
    
    /**
     * @var string
     */
    protected $containerSpaceBefore = '';
    
    /**
     * @var string
     */
    protected $containerSpaceAfter = '';

    /**
     * @var string
     */
    protected $ceHeader = '';

    /**
     * @var string
     */
    protected $ceSubheader = '';

    /**
     * @var string
     */
    protected $ceRte = '';
    
    /**
     * @var string
     */
    protected $ceColorpicker = '';

    /**
     * @var int
     */
    protected $txContainerParent = 0;

    /**
     * @var int
     */
    protected $containerRecords = 0;

    /**
     * @var int
     */
    protected $containerNoGutters = 0;

    /**
     * @var bool
     */
    protected $isIrre = false;

    /**
     * @var string
     */
    protected $containerBackground = '';

    /**
     * @var int
     */
    protected $sorting = 0;

    /**
     * @var bool
     */
    protected $containerIsFluid = false;

    /**
     * @var bool
     */
    protected $containerFullHeight = false;

    /**
     * @var bool
     */
    protected $ceCheckbox = false;

    /**
     * @var bool
     */
    protected $ceHascheckbox = false;

    /**
     * @param string $CType
     */
    public function setCType($CType)
    {
        $this->CType = $CType;
    }

    /**
     * @return string
     */
    public function getCType()
    {
        return $this->CType;
    }

    /**
     * @param string $header
     */
    public function setHeader(string$header)
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $subheader
     */
    public function setSubheader($subheader)
    {
        $this->subheader = $subheader;
    }

    /**
     * @return string
     */
    public function getSubheader()
    {
        return $this->subheader;
    }

    /**
     * @param string $colPos
     */
    public function setColPos($colPos)
    {
        $this->colPos = $colPos;
    }

    /**
     * @return string
     */
    public function getColPos()
    {
        return $this->colPos;
    }

    /**
     * @param string $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param string $parentTable
     */
    public function setParentTable($parentTable)
    {
        $this->parentTable = $parentTable;
    }

    /**
     * @return string
     */
    public function getParentTable()
    {
        return $this->parentTable;
    }

    /**
     * @param string $containerSpaceBefore
     */
    public function setContainerSpaceBefore($containerSpaceBefore)
    {
        $this->containerSpaceBefore = $containerSpaceBefore;
    }

    /**
     * @return string
     */
    public function getContainerSpaceBefore()
    {
        return $this->containerSpaceBefore;
    }

    /**
     * @param string $containerSpaceAfter
     */
    public function setContainerSpaceAfter($containerSpaceAfter)
    {
        $this->containerSpaceAfter = $containerSpaceAfter;
    }

    /**
     * @return string
     */
    public function getContainerSpaceAfter()
    {
        return $this->containerSpaceAfter;
    }

    /**
     * @param string $containerBackground
     */
    public function setContainerBackground($containerBackground)
    {
        $this->containerBackground = $containerBackground;
    }

    /**
     * @return string
     */
    public function getContainerBackground()
    {
        return $this->containerBackground;
    }

    /**
     * @param string $ceHeader
     */
    public function setCeHeader($ceHeader)
    {
        $this->ceHeader = $ceHeader;
    }

    /**
     * @return string
     */
    public function getCeHeader()
    {
        return $this->ceHeader;
    }

    /**
     * @param string $ceSubheader
     */
    public function setCeSubheader($ceSubheader)
    {
        $this->ceSubheader = $ceSubheader;
    }

    /**
     * @return string
     */
    public function getCeSubheader()
    {
        return $this->ceSubheader;
    }

    /**
     * @param string $ceRte
     */
    public function setCeRte($ceRte)
    {
        $this->ceRte = $ceRte;
    }

    /**
     * @return string
     */
    public function getCeRte()
    {
        return $this->ceRte;
    }

    /**
     * @param string $ceColorpicker
     */
    public function setCeColorpicker($ceColorpicker)
    {
        $this->ceColorpicker = $ceColorpicker;
    }

    /**
     * @return string
     */
    public function getCeColorpicker()
    {
        return $this->ceColorpicker;
    }

    /**
     * @param int $sorting
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }

    /**
     * @return int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param int $txContainerParent
     */
    public function setTxContainerParent($txContainerParent)
    {
        $this->txContainerParent = $txContainerParent;
    }

    /**
     * @return int
     */
    public function getTxContainerParent()
    {
        return $this->txContainerParent;
    }

    /**
     * @param int $containerRecords
     */
    public function setContainerRecords($containerRecords)
    {
        $this->containerRecords = $containerRecords;
    }

    /**
     * @return int
     */
    public function getContainerRecords()
    {
        return $this->containerRecords;
    }

    /**
     * @param int $containerNoGutters
     */
    public function setContainerNoGutters($containerNoGutters)
    {
        $this->containerNoGutters = $containerNoGutters;
    }

    /**
     * @return int
     */
    public function getContainerNoGutters()
    {
        return $this->containerNoGutters;
    }

    /**
     * @param bool $isIrre
     */
    public function setIsIrre($isIrre)
    {
        $this->isIrre = $isIrre;
    }

    /**
     * @return bool
     */
    public function getIsIrre()
    {
        return $this->isIrre;
    }

    /**
     * @param bool $containerIsFluid
     */
    public function setContainerIsFluid($containerIsFluid)
    {
        $this->containerIsFluid = $containerIsFluid;
    }

    /**
     * @return bool
     */
    public function getContainerIsFluid()
    {
        return $this->containerIsFluid;
    }

    /**
     * @param bool $containerFullHeight
     */
    public function setContainerFullHeight($containerFullHeight)
    {
        $this->containerFullHeight = $containerFullHeight;
    }

    /**
     * @return bool
     */
    public function getContainerFullHeight()
    {
        return $this->containerFullHeight;
    }

    /**
     * @param bool $ceCheckbox
     */
    public function setCeCheckbox($ceCheckbox)
    {
        $this->ceCheckbox = $ceCheckbox;
    }

    /**
     * @return bool
     */
    public function getCeCheckbox()
    {
        return $this->ceCheckbox;
    }

    /**
     * @param bool $ceHascheckbox
     */
    public function setCeHascheckbox($ceHascheckbox)
    {
        $this->ceHascheckbox = $ceHascheckbox;
    }

    /**
     * @return bool
     */
    public function getCeHascheckbox()
    {
        return $this->ceHascheckbox;
    }
    
}
