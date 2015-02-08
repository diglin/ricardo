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

/**
 * Class ArticleInformationParameter
 * @package Diglin\Ricardo\Managers\Sell
 */
class ArticleInformationParameter extends ParameterAbstract
{
    /**
     * @var int
     */
    protected $_articleConditionId; // required

    /**
     * @var int
     */
    protected $_articleDuration; // required

    /**
     * @var int
     */
    protected $_availabilityId; // required

    /**
     * @var float
     */
    protected $_buyNowPrice; // optional

    /**
     * @var int
     */
    protected $_categoryId; // required

    /**
     * @var array of ArticleDeliveryParameter
     */
    protected $_deliveries = array(); // required

    /**
     * @var int
     */
    protected $_increment = null; // optional

    /**
     * @var int
     */
    protected $_initialQuantity; // required

    /**
     * @var array
     */
    protected $_internalReferences = array(); // optional

    /**
     * @var boolean
     */
    protected $_isCustomerTemplate; // required

    /**
     * @var boolean
     */
    protected $_isRelistSoldOut; // required

    /**
     * @var int
     */
    protected $_mainPictureId; // required

    /**
     * @var int
     */
    protected $_maxRelistCount; // required

    /**
     * @var array
     */
    protected $_paymentConditionIds; // optional

    /**
     * @var array
     */
    protected $_paymentMethodIds; // optional

    /**
     * @var array
     */
    protected $_promotionIds; // optional - required if BuyNow

    /**
     * @var string
     */
    protected $_startDate; // optional

    /**
     * @var float
     */
    protected $_startPrice = null; // optional

    /**
     * @var int
     */
    protected $_templateId; // optional

    /**
     * @var int
     */
    protected $_warrantyId; // required

    protected $_requiredProperties = array(
        'articleConditionId',
        'articleDuration',
        'availabilityId',
        'categoryId',
        'initialQuantity',
        'isCustomerTemplate',
        'isRelistSoldOut',
        'mainPictureId',
        'maxRelistCount',
        'warrantyId',
        'promotionIds',
        'deliveries',
    );

    protected $_optionalProperties = array(
        'buyNowPrice',
        'increment',
        'internalReferences',
        'paymentConditionIds',
        'paymentMethodIds',
        'startDate',
        'startPrice',
        'templateId'
    );

    /**
     * @param int $articleConditionId
     * @return $this
     */
    public function setArticleConditionId($articleConditionId)
    {
        $this->_articleConditionId = $articleConditionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleConditionId()
    {
        return (int) $this->_articleConditionId;
    }

    /**
     * @param int $articleDuration
     * @return $this
     */
    public function setArticleDuration($articleDuration)
    {
        $this->_articleDuration = $articleDuration;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleDuration()
    {
        return (int) $this->_articleDuration;
    }

    /**
     * @param int $availabilityId
     * @return $this
     */
    public function setAvailabilityId($availabilityId)
    {
        $this->_availabilityId = $availabilityId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAvailabilityId()
    {
        return (int) $this->_availabilityId;
    }

    /**
     * @param float $buyNowPrice
     * @return $this
     */
    public function setBuyNowPrice($buyNowPrice)
    {
        $this->_buyNowPrice = $buyNowPrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getBuyNowPrice()
    {
        return floatval($this->_buyNowPrice);
    }

    /**
     * @param int $categoryId
     * @return $this
     */
    public function setCategoryId($categoryId)
    {
        $this->_categoryId = $categoryId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return (int) $this->_categoryId;
    }

    /**
     * @param \Diglin\Ricardo\Managers\Sell\Parameter\ArticleDeliveryParameter $deliveries
     * @param bool $clear
     * @return $this
     */
    public function setDeliveries(ArticleDeliveryParameter $deliveries, $clear = false)
    {
        if ($clear) {
            $this->_deliveries = array();
        }
        $this->_deliveries[] = $deliveries;
        return $this;
    }

    /**
     * @return array of \Diglin\Ricardo\Managers\Sell\Parameter\ArticleDeliveryParameter
     */
    public function getDeliveries()
    {
        return (array) $this->_deliveries;
    }

    /**
     * @param int $increment
     * @return $this
     */
    public function setIncrement($increment)
    {
        $this->_increment = (int) $increment;
        return $this;
    }

    /**
     * @return int
     */
    public function getIncrement()
    {
        return $this->_increment;
    }

    /**
     * @param int $initialQuantity
     * @return $this
     */
    public function setInitialQuantity($initialQuantity)
    {
        $this->_initialQuantity = $initialQuantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getInitialQuantity()
    {
        return (int) $this->_initialQuantity;
    }

    /**
     * @param ArticleInternalReferenceParameter $internalReferences
     * @param bool $clear
     * @return $this
     */
    public function setInternalReferences(ArticleInternalReferenceParameter $internalReferences, $clear = false)
    {
        if ($clear) {
            $this->_internalReferences = array();
        }
        $this->_internalReferences[] = $internalReferences;
        return $this;
    }

    /**
     * @return \Diglin\Ricardo\Managers\Sell\Parameter\ArticleInternalReferenceParameter
     */
    public function getInternalReferences()
    {
        return $this->_internalReferences;
    }

    /**
     * Must be set to "true" if templateId is provided
     *
     * @param boolean $isCustomerTemplate
     * @return $this
     */
    public function setIsCustomerTemplate($isCustomerTemplate)
    {
        $this->_isCustomerTemplate = $isCustomerTemplate;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsCustomerTemplate()
    {
        return (bool) $this->_isCustomerTemplate;
    }

    /**
     * @param boolean $isRelistSoldOut
     * @return $this
     */
    public function setIsRelistSoldOut($isRelistSoldOut)
    {
        $this->_isRelistSoldOut = $isRelistSoldOut;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRelistSoldOut()
    {
        return (bool) $this->_isRelistSoldOut;
    }

    /**
     * @param int $mainPictureId
     * @return $this
     */
    public function setMainPictureId($mainPictureId)
    {
        $this->_mainPictureId = $mainPictureId;
        return $this;
    }

    /**
     * @return int
     */
    public function getMainPictureId()
    {
        return (int) $this->_mainPictureId;
    }

    /**
     * @param int $maxRelistCount
     * @return $this
     */
    public function setMaxRelistCount($maxRelistCount)
    {
        $this->_maxRelistCount = $maxRelistCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxRelistCount()
    {
        return (int) $this->_maxRelistCount;
    }

    /**
     * @param array $paymentConditionIds
     * @return $this
     */
    public function setPaymentConditionIds($paymentConditionIds)
    {
        $this->_paymentConditionIds = $paymentConditionIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getPaymentConditionIds()
    {
        return (array) $this->_paymentConditionIds;
    }

    /**
     * @param array $paymentMethodIds
     * @return $this
     */
    public function setPaymentMethodIds($paymentMethodIds)
    {
        $this->_paymentMethodIds = $paymentMethodIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getPaymentMethodIds()
    {
        return (array) $this->_paymentMethodIds;
    }

    /**
     * @param array $promotionIds
     * @return $this
     */
    public function setPromotionIds($promotionIds)
    {
        $this->_promotionIds = $promotionIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getPromotionIds()
    {
        return (array) $this->_promotionIds;
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
     * @return string
     */
    public function getStartDate()
    {
        return $this->_startDate;
    }

    /**
     * @param float $startPrice
     * @return $this
     */
    public function setStartPrice($startPrice)
    {
        $this->_startPrice =  floatval($startPrice);
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
     * setIsCustomerTemplate must be set to true if you set the templateId here
     *
     * @param int $templateId
     * @return $this
     */
    public function setTemplateId($templateId)
    {
        $this->_templateId = $templateId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTemplateId()
    {
        return $this->_templateId;
    }

    /**
     * @param int $warrantyId
     * @return $this
     */
    public function setWarrantyId($warrantyId)
    {
        $this->_warrantyId = $warrantyId;
        return $this;
    }

    /**
     * @return int
     */
    public function getWarrantyId()
    {
        return (int) $this->_warrantyId;
    }
}
