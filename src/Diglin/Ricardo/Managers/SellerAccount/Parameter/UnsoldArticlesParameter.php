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

use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Enums\Customer\OpenArticlesSortBy;
use Diglin\Ricardo\Enums\Customer\PaidStatusFilter;
use Diglin\Ricardo\Enums\Customer\ShippedStatusFilter;
use \Diglin\Ricardo\Managers\ParameterAbstract;

class SoldArticlesParameter extends ParameterAbstract
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
     * @var int
     */
    protected $_articleTypeFilter = ArticlesTypes::ALL;

    /**
     * @var bool
     */
    protected $_ascendingSort = true;

    /**
     * @var string
     */
    protected $_internalReferenceFilter;

    /**
     * @var string
     */
    protected $_lastnameFilter;

    /**
     * @var string
     */
    protected $_nicknameFilter;

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

    // new

    /**
     * @var bool
     */
    protected $_isArchived;

    /**
     * @var bool
     */
    protected $_isCompletedTransaction;

    protected $_maximumEndDate;

    protected $_minimumEndDate;

    protected $_paidStatusFilter = PaidStatusFilter::ANY;

    protected $_shippedStatusFilter = ShippedStatusFilter::ANY;

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

    /**
     * @param boolean $isArchived
     * @return $this
     */
    public function setIsArchived($isArchived)
    {
        $this->_isArchived = (bool) $isArchived;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsArchived()
    {
        return (bool) $this->_isArchived;
    }

    /**
     * @param boolean $isCompletedTransaction
     * @return $this
     */
    public function setIsCompletedTransaction($isCompletedTransaction)
    {
        $this->_isCompletedTransaction = (bool) $isCompletedTransaction;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsCompletedTransaction()
    {
        return (bool) $this->_isCompletedTransaction;
    }

    /**
     * @param mixed $maximumEndDate
     * @return $this
     */
    public function setMaximumEndDate($maximumEndDate)
    {
        $this->_maximumEndDate = $maximumEndDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaximumEndDate()
    {
        return $this->_maximumEndDate;
    }

    /**
     * @param mixed $minimumEndDate
     * @return $this
     */
    public function setMinimumEndDate($minimumEndDate)
    {
        $this->_minimumEndDate = $minimumEndDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinimumEndDate()
    {
        return $this->_minimumEndDate;
    }

    /**
     * @param int $paidStatusFilter
     * @return $this
     */
    public function setPaidStatusFilter($paidStatusFilter)
    {
        $this->_paidStatusFilter = (int) $paidStatusFilter;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaidStatusFilter()
    {
        return (int) $this->_paidStatusFilter;
    }

    /**
     * @param int $shippedStatusFilter
     * @return $this
     */
    public function setShippedStatusFilter($shippedStatusFilter)
    {
        $this->_shippedStatusFilter = (int) $shippedStatusFilter;
        return $this;
    }

    /**
     * @return int
     */
    public function getShippedStatusFilter()
    {
        return (int) $this->_shippedStatusFilter;
    }
}