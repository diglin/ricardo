<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Managers\SellerAccount\Parameter;

use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Enums\CloseListStatus;
use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class ArticlesParameter
 * @package Diglin\Ricardo\Managers\SellerAccount\Parameter
 */
class ArticlesParameter extends ParameterAbstract
{
    /**
     * @var int
     */
    protected $_articlesType = ArticlesTypes::ALL;

    /**
     * @var int
     */
    protected $_closeStatus = CloseListStatus::OPEN;

    /**
     * @var bool
     */
    protected $_isPlannedArticles = false;

    /**
     * @var null
     */
    protected $_lastModificationDate = null;

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'articlesType',
        'closeStatus',
    );

    protected $_optionalProperties = array(
        'isPlannedArticles',
        'lastModificationDate',
    );

    /**
     * @param int $articlesType
     * @return $this
     */
    public function setArticlesType($articlesType)
    {
        $this->_articlesType = $articlesType;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticlesType()
    {
        return $this->_articlesType;
    }

    /**
     * @param int $closeStatus
     * @return $this
     */
    public function setCloseStatus($closeStatus)
    {
        $this->_closeStatus = $closeStatus;
        return $this;
    }

    /**
     * @return int
     */
    public function getCloseStatus()
    {
        return $this->_closeStatus;
    }

    /**
     * @param boolean $isPlannedArticles
     * @return $this
     */
    public function setIsPlannedArticles($isPlannedArticles)
    {
        $this->_isPlannedArticles = (bool) $isPlannedArticles;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsPlannedArticles()
    {
        return (bool) $this->_isPlannedArticles;
    }

    /**
     * @param null $lastModificationDate
     * @return $this
     */
    public function setLastModificationDate($lastModificationDate)
    {
        $this->_lastModificationDate = $lastModificationDate;
        return $this;
    }

    /**
     * @return null
     */
    public function getLastModificationDate()
    {
        return $this->_lastModificationDate;
    }


}