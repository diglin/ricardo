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
 * Class ArticleDeliveryParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
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
