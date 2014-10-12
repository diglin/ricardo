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
 * Class Diglin_Ricento_Model_Config_Source_Rules_Shipping
 */
class Diglin_Ricento_Model_Config_Source_Rules_Shipping extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const TYPE_OTHER = 0;

    /**
     * @var array
     */
    protected $_shippingConditions = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_shippingConditions)) {
            $shippingConditions = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getDeliveryConditions();

            foreach ($shippingConditions as $shippingCondition)
                if (isset($shippingCondition['DeliveryConditionId'])) {
                    $this->_shippingConditions[$shippingCondition['DeliveryConditionId']] = $shippingCondition['DeliveryConditionText'];
                }
        }
        return $this->_shippingConditions;
    }
}