<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Packages extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const TYPE_OTHER = 0;

    protected $_shippingPackages = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_shippingConditions)) {
            $shippingConditions = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getDeliveryConditions();

            foreach ($shippingConditions as $shippingCondition)
                if (!empty($shippingCondition['PackageSizes'])) {
                    $this->_shippingPackages[$shippingCondition['DeliveryConditionId']] = $shippingCondition['PackageSizes'];
                } else {
                    $this->_shippingPackages[$shippingCondition['DeliveryConditionId']] = $shippingCondition['DeliveryCost'];
                }
        }
        return $this->_shippingPackages;
    }
}