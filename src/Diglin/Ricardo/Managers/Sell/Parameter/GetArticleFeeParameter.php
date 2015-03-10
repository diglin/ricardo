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
namespace Diglin\Ricardo\Managers\Sell\Parameter;

use Diglin\Ricardo\Managers\ParameterAbstract;
use Diglin\Ricardo\Enums\Article\PromotionCode;

/**
 * Class GetArticleFeeParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class GetArticleFeeParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_articleCondition; // required

    /**
     * @var float
     */
    protected $_buyNowPrice; // optional

    /**
     * @var int
     */
    protected $_categoryId; // required

    /**
     * @var bool
     */
    protected $_excludeListingFees; // required

    /**
     * @var int
     */
    protected $_initialQuantity; // required

    /**
     * @var int
     */
    protected $_pictureCount; // required

    /**
     * @var array PromotionCode
     */
    protected $_promotionIds; // required

    /**
     * @var string
     */
    protected $_startDate; // required

    /**
     * @var float
     */
    protected $_startPrice; // optional

    protected $_requiredProperties = array(
        'articleCondition',
        'categoryId',
        'excludeListingFees',
        'initialQuantity',
        'pictureCount',
        'promotionIds',
        'startDate',
    );

    protected $_optionalProperties = array(
        'buyNowPrice',
        'startPrice'
    );

    /**
     * @return string
     */
    public function getArticleCondition()
    {
        return $this->_articleCondition;
    }

    /**
     * @param string $articleCondition
     * @return $this
     */
    public function setArticleCondition($articleCondition)
    {
        $this->_articleCondition = $articleCondition;
        return $this;
    }

    /**
     * @return float
     */
    public function getBuyNowPrice()
    {
        return $this->_buyNowPrice;
    }

    /**
     * @param float $buyNowPrice
     * @return $this
     */
    public function setBuyNowPrice($buyNowPrice)
    {
        $this->_buyNowPrice = (float) $buyNowPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->_categoryId;
    }

    /**
     * @param int $categoryId
     * @return $this
     */
    public function setCategoryId($categoryId)
    {
        $this->_categoryId = (int) $categoryId;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getExcludeListingFees()
    {
        return (bool) $this->_excludeListingFees;
    }

    /**
     * @param boolean $excludeListingFees
     * @return $this
     */
    public function setExcludeListingFees($excludeListingFees)
    {
        $this->_excludeListingFees = (bool) $excludeListingFees;
        return $this;
    }

    /**
     * @return int
     */
    public function getInitialQuantity()
    {
        return $this->_initialQuantity;
    }

    /**
     * @param int $initialQuantity
     * @return $this
     */
    public function setInitialQuantity($initialQuantity)
    {
        $this->_initialQuantity = (int) $initialQuantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getPictureCount()
    {
        return $this->_pictureCount;
    }

    /**
     * @param int $pictureCount
     * @return $this
     */
    public function setPictureCount($pictureCount)
    {
        $this->_pictureCount = $pictureCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getPromotionIds()
    {
        return $this->_promotionIds;
    }

    /**
     * @param array $promotionIds
     * @return $this
     */
    public function setPromotionIds($promotionIds)
    {
        $this->_promotionIds = (array) $promotionIds;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->_startDate;
    }

    /**
     * @param string $startDate
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->_startDate = $startDate;
        return $this;
    }

    /**
     * @return float
     */
    public function getStartPrice()
    {
        return $this->_startPrice;
    }

    /**
     * @param float $startPrice
     * @return $this
     */
    public function setStartPrice($startPrice)
    {
        $this->_startPrice = (float) $startPrice;
        return $this;
    }
}
