<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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
     * @var ArticleDeliveryParameter
     */
    protected $_deliveries; // optional

    /**
     * @var float
     */
    protected $_increment; // optional

    /**
     * @var int
     */
    protected $_initialQuantity; // required

    /**
     * @var ArticleInternalReferenceParameter
     */
    protected $_internalReferences; // optional

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
    protected $_promotionIds; // optional

    /**
     * @var string
     */
    protected $_startDate; // optional

    /**
     * @var float
     */
    protected $_startPrice; // optional

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
    );

    protected $_optionalProperties = array(
        'buyNowPrice',
        'deliveries',
        'increment',
        'internalReferences',
        'paymentConditionIds',
        'paymentMethodIds',
        'promotionIds',
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
        return $this->_articleConditionId;
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
        return $this->_articleDuration;
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
        return $this->_availabilityId;
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
        return $this->_buyNowPrice;
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
        return $this->_categoryId;
    }

    /**
     * @param \Diglin\Ricardo\Managers\Sell\Parameter\ArticleDeliveryParameter $deliveries
     * @return $this
     */
    public function setDeliveries(ArticleDeliveryParameter $deliveries)
    {
        $this->_deliveries = $deliveries;
        return $this;
    }

    /**
     * @return \Diglin\Ricardo\Managers\Sell\Parameter\ArticleDeliveryParameter
     */
    public function getDeliveries()
    {
        return $this->_deliveries;
    }

    /**
     * @param float $increment
     * @return $this
     */
    public function setIncrement($increment)
    {
        $this->_increment = $increment;
        return $this;
    }

    /**
     * @return float
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
        return $this->_initialQuantity;
    }

    /**
     * @param \Diglin\Ricardo\Managers\Sell\Parameter\ArticleInternalReferenceParameter $internalReferences
     * @return $this
     */
    public function setInternalReferences(ArticleInternalReferenceParameter $internalReferences)
    {
        $this->_internalReferences = $internalReferences;
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
        return $this->_isCustomerTemplate;
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
        return $this->_isRelistSoldOut;
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
        return $this->_mainPictureId;
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
        return $this->_maxRelistCount;
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
        return $this->_paymentConditionIds;
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
        return $this->_paymentMethodIds;
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
        return $this->_promotionIds;
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
        $this->_startPrice = $startPrice;
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
     * @param int $templateId
     * @return $this
     */
    public function setTemplateId($templateId)
    {
        $this->_templateId = $templateId;
        return $this;
    }

    /**
     * @return int
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
        return $this->_warrantyId;
    }


}