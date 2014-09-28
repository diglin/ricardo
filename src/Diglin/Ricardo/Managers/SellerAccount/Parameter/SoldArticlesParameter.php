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
use Diglin\Ricardo\Enums\Customer\OpenArticlesSortBy;
use \Diglin\Ricardo\Managers\ParameterAbstract;

class OpenArticlesParameter extends ParameterAbstract
{
    protected $_articleIdsFilter = array();

    protected $_articleTitleFilter;

    protected $_articleTypeFilter = ArticlesTypes::ALL;

    protected $_ascendingSort = true;

    protected $_internalReferenceFilter;

    protected $_lastnameFilter;

    protected $_nicknameFilter;

    protected $_pageNumber = null;

    protected $_pageSize = null;

    protected $_sortBy = null;

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'articleTitleFilter',
        'articleTypeFilter',
        'internalReferenceFilter',
        'lastnameFilter',
        'nicknameFilter',
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
     * @param int $articleTypeFilter
     * @return $this
     */
    public function setArticleTypeFilter($articleTypeFilter)
    {
        $this->_articleTypeFilter = $articleTypeFilter;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleTypeFilter()
    {
        $types = new ArticlesTypes();
        if (!in_array($this->_articleTypeFilter, $types->getValues())) {
            $this->_articleTypeFilter = ArticlesTypes::ALL;
        }
        return $this->_articleTypeFilter;
    }

    /**
     * @param boolean $ascendingSort
     * @return $this
     */
    public function setAscendingSort($ascendingSort)
    {
        $this->_ascendingSort = (bool) $ascendingSort;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAscendingSort()
    {
        return (bool) $this->_ascendingSort;
    }

    /**
     * @param mixed $internalReferenceFilter
     * @return $this
     */
    public function setInternalReferenceFilter($internalReferenceFilter)
    {
        $this->_internalReferenceFilter = $internalReferenceFilter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInternalReferenceFilter()
    {
        return $this->_internalReferenceFilter;
    }

    /**
     * @param mixed $lastnameFilter
     * @return $this
     */
    public function setLastnameFilter($lastnameFilter)
    {
        $this->_lastnameFilter = $lastnameFilter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastnameFilter()
    {
        return $this->_lastnameFilter;
    }

    /**
     * @param mixed $nicknameFilter
     * @return $this
     */
    public function setNicknameFilter($nicknameFilter)
    {
        $this->_nicknameFilter = $nicknameFilter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNicknameFilter()
    {
        return $this->_nicknameFilter;
    }

    /**
     * @param null $pageNumber
     * @return $this
     */
    public function setPageNumber($pageNumber)
    {
        $this->_pageNumber = $pageNumber;
        return $this;
    }

    /**
     * @return null
     */
    public function getPageNumber()
    {
        return $this->_pageNumber;
    }

    /**
     * @param null $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->_pageSize = (int) $pageSize;
        return $this;
    }

    /**
     * @return null
     */
    public function getPageSize()
    {
        return (int) $this->_pageSize;
    }

    /**
     * @param null $sortBy
     * @return $this
     */
    public function setSortBy($sortBy)
    {
        $this->_sortBy = (int) $sortBy;
        return $this;
    }

    /**
     * @return null
     */
    public function getSortBy()
    {
        return (int) $this->_sortBy;
    }
}