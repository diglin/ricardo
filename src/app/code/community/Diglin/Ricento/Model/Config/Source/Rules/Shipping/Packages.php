<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Packages
 */
class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Packages extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const TYPE_OTHER = 0;

    /**
     * @var array
     */
    protected $_shippingPackages = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_shippingConditions) && Mage::helper('diglin_ricento')->isConfigured()) {
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