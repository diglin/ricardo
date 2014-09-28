<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Diglin\Ricardo\Managers\SellerAccount\Parameter;

use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class PlannedArticlesParameter
 * @package Diglin\Ricardo\Managers\SellerAccount\Parameter
 */
class PlannedArticlesParameter extends ParameterAbstract
{
    /**
     * @var array
     */
    protected $_articleIdsFilter = array();

    /**
     * @var string
     */
    protected $_articleTitleFilter;

    /**
     * @var boolean
     */
    protected $_ascendingSort;

    /**
     * @var string
     */
    protected $_internalReferenceFilter;

    /**
     * @var int
     */
    protected $_pageNumber;

    /**
     * @var int
     */
    protected $_pageSize;

    /**
     * @var int
     */
    protected $_sortBy;

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'articleTitleFilter',
        'internalReferenceFilter',
    );

    protected $_optionalProperties = array(
        'articleIdsFilter',
        'ascendingSort',
        'pageNumber',
        'pageSize',
        'sortBy',
    );

    /**
     * @param array $articleIdsFilter
     * @return $this
     */
    public function setArticleIdsFilter($articleIdsFilter)
    {
        $this->_articleIdsFilter = $articleIdsFilter;
        return $this;
    }

    /**
     * @return array
     */
    public function getArticleIdsFilter()
    {
        return (array) $this->_articleIdsFilter;
    }

    /**
     * @param mixed $articleTitleFilter
     * @return $this
     */
    public function setArticleTitleFilter($articleTitleFilter)
    {
        $this->_articleTitleFilter = $articleTitleFilter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticleTitleFilter()
    {
        return $this->_articleTitleFilter;
    }

    /**
     * @param bool $ascendingSort
     * @return $this
     */
    public function setAscendingSort($ascendingSort)
    {
        $this->_ascendingSort = $ascendingSort;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAscendingSort()
    {
        return (bool) $this->_ascendingSort;
    }

    /**
     * @param int $pageNumber
     * @return $this
     */
    public function setPageNumber($pageNumber)
    {
        $this->_pageNumber = $pageNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return (int) $this->_pageNumber;
    }

    /**
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->_pageSize = $pageSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return (int) $this->_pageSize;
    }

    /**
     * @param int $sortBy
     * @return $this
     */
    public function setSortBy($sortBy)
    {
        $this->_sortBy = $sortBy;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortBy()
    {
        return (int) $this->_sortBy;
    }

    /**
     * @param string $internalReferenceFilter
     * @return $this
     */
    public function setInternalReferenceFilter($internalReferenceFilter)
    {
        $this->_internalReferenceFilter = $internalReferenceFilter;
        return $this;
    }

    /**
     * @return string
     */
    public function getInternalReferenceFilter()
    {
        return $this->_internalReferenceFilter;
    }
}