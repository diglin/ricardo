<?php
/**
 * Diglin GmbH - Switzerland
 *
 * This file is part of a Diglin GmbH module.
 *
 * This Diglin GmbH module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
namespace Diglin\Ricardo\Managers\SellerAccount\Parameter;

use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Enums\Article\CloseListStatus;
use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class GetInTransitionArticlesParameter
 * @package Diglin\Ricardo\Managers\SellerAccount\Parameter
 */
class GetInTransitionArticlesParameter extends ParameterAbstract
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
     * @var string
     */
    protected $_articleTypeFilter;

    /**
     * @var boolean
     */
    protected $_ascendingSort;

    /**
     * @var string
     */
    protected $_internalReferenceFilter;

    /**
     * @var string
     */
    protected $_lastname;

    /**
     * @var string
     */
    protected $_nickname;

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
     * @var int
     */
    protected $_transitionStatusFilter;

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'articleTitleFilter',
        'articleTypeFilter',
        'lastname',
        'nickname',
        'articleTypeFilter',
        'internalReferenceFilter',
        'transitionStatusFilter',
    );

    protected $_optionalProperties = array(
        'articleIdsFilter',
        'ascendingSort',
        'pageNumber',
        'pageSize',
        'sortBy',
    );

    /**
     * @return array
     */
    public function getArticleIdsFilter()
    {
        return $this->_articleIdsFilter;
    }

    /**
     * @param array $articleIdsFilter
     * @return $this
     */
    public function setArticleIdsFilter(array $articleIdsFilter)
    {
        $this->_articleIdsFilter = $articleIdsFilter;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleTitleFilter()
    {
        return $this->_articleTitleFilter;
    }

    /**
     * @param string $articleTitleFilter
     * @return $this
     */
    public function setArticleTitleFilter($articleTitleFilter)
    {
        $this->_articleTitleFilter = $articleTitleFilter;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleTypeFilter()
    {
        return $this->_articleTypeFilter;
    }

    /**
     * @param string $articleTypeFilter
     * @return $this
     */
    public function setArticleTypeFilter($articleTypeFilter)
    {
        $this->_articleTypeFilter = $articleTypeFilter;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAscendingSort()
    {
        return $this->_ascendingSort;
    }

    /**
     * @param boolean $ascendingSort
     * @return $this
     */
    public function setAscendingSort($ascendingSort)
    {
        $this->_ascendingSort = $ascendingSort;

        return $this;
    }

    /**
     * @return string
     */
    public function getInternalReferenceFilter()
    {
        return $this->_internalReferenceFilter;
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
    public function getLastname()
    {
        return $this->_lastname;
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->_lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->_nickname;
    }

    /**
     * @param string $nickname
     * @return $this
     */
    public function setNickname($nickname)
    {
        $this->_nickname = $nickname;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->_pageNumber;
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
    public function getPageSize()
    {
        return $this->_pageSize;
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
    public function getSortBy()
    {
        return $this->_sortBy;
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
    public function getTransitionStatusFilter()
    {
        return $this->_transitionStatusFilter;
    }

    /**
     * @param int $transitionStatusFilter
     * @return $this
     */
    public function setTransitionStatusFilter($transitionStatusFilter)
    {
        $this->_transitionStatusFilter = $transitionStatusFilter;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequiredProperties()
    {
        return (array) $this->_requiredProperties;
    }

    /**
     * @param array $requiredProperties
     * @return $this
     */
    public function setRequiredProperties(array $requiredProperties)
    {
        $this->_requiredProperties = $requiredProperties;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptionalProperties()
    {
        return (array) $this->_optionalProperties;
    }

    /**
     * @param array $optionalProperties
     * @return $this
     */
    public function setOptionalProperties(array $optionalProperties)
    {
        $this->_optionalProperties = $optionalProperties;

        return $this;
    }
}
