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

class ArticleDeliveryParameter extends ParameterAbstract
{
    /**
     * @var float
     */
    protected $_deliveryCost; // required

    /**
     * @var int
     */
    protected $_deliveryId; // required

    /**
     * @var int
     */
    protected $_deliveryPackageSizeId; // optional

    /**
     * @var boolean
     */
    protected $_isCumulativeShipping; // required

    /**
     * @var boolean
     */
    protected $_isDeliveryFree; // required

    protected $_requiredProperties = array(
        'deliveryCost',
        'deliveryId',
        'isCumulativeShipping',
        'isDeliveryFree',
    );

    protected $_optionalProperties = array(
        'deliveryPackageSizeId',
    );

    /**
     * @param int $deliveryPackageSizeId
     * @return $this
     */
    public function setDeliveryPackageSizeId($deliveryPackageSizeId)
    {
        $this->_deliveryPackageSizeId = (int) $deliveryPackageSizeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryPackageSizeId()
    {
        return (int) $this->_deliveryPackageSizeId;
    }

    /**
     * @param boolean $isCumulativeShipping
     * @return $this
     */
    public function setIsCumulativeShipping($isCumulativeShipping)
    {
        $this->_isCumulativeShipping = (bool) $isCumulativeShipping;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsCumulativeShipping()
    {
        return (bool) $this->_isCumulativeShipping;
    }

    /**
     * @param float $deliveryCost
     * @return $this
     */
    public function setDeliveryCost($deliveryCost)
    {
        $this->_deliveryCost = (float) $deliveryCost;
        return $this;
    }

    /**
     * @return float
     */
    public function getDeliveryCost()
    {
        return floatval($this->_deliveryCost);
    }

    /**
     * @param int $deliveryId
     * @return $this
     */
    public function setDeliveryId($deliveryId)
    {
        $this->_deliveryId = (int) $deliveryId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryId()
    {
        return (int) $this->_deliveryId;
    }

    /**
     * @param boolean $isDeliveryFree
     * @return $this
     */
    public function setIsDeliveryFree($isDeliveryFree = false)
    {
        $this->_isDeliveryFree = (bool) $isDeliveryFree;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsDeliveryFree()
    {
        return (bool) $this->_isDeliveryFree;
    }
}