<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Rules_Shipping
 */
class Diglin_Ricento_Model_Config_Source_Rules_Shipping extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const TYPE_OTHER = 0;

    /**
     * @return array
     */
//    public function toOptionHash()
//    {
//        // TODO: implement
//        return array(
//            '' => '- Please Select -',
//            1  => 'Take away',
//            2  => 'Mail A Post',
//            3  => 'Mail B Post',
//            4  => 'Package A Post',
//            5  => 'Package B Post',
//            6  => 'DHL',
//            7  => 'DPS',
//            8  => 'UPS',
//            9  => 'TNT',
//            10 => 'Flat',
//            self::TYPE_OTHER => 'Other (description)'
//        );
//
    /**
     * @return array
     */
    public function toOptionHash()
    {
        $shippingConditions = Mage::getSingleton('diglin_ricento/api_services_system')->getDeliveryConditions();

        $shippingMethods = array();

        foreach ($shippingConditions as $shippingCondition)
        if (isset($shippingCondition['DeliveryConditionId'])) {
            $shippingMethods[$shippingCondition['DeliveryConditionId']] = $shippingCondition['DeliveryConditionText'];
        }
        return $shippingMethods;
    }
}