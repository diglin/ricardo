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
 * Class Diglin_Ricento_Model_Config_Source_Rules_Payment
 */
class Diglin_Ricento_Model_Config_Source_Rules_Payment extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const TYPE_BANK_TRANSFER = 8192;
    const TYPE_CASH = 1073741824;
    const TYPE_CREDIT_CARD = 262144;
    const TYPE_OTHER = 0;

    /**
     * @return array
     */
//    public function toOptionHash()
//    {
//        // TODO: implement
//        return array(
//            self::TYPE_BANK_TRANSFER => 'Bank Transfer',
//            self::TYPE_CASH          => 'Cash',
//            self::TYPE_CREDIT_CARD   => 'Credit Card (Payu - Ricardo service)',
//            self::TYPE_OTHER         => 'Other (fill the description)'
//        );
//    }

    /**
     * @return array
     */
    public function toOptionHash()
    {
        // @todo check if we translate in our own way, check how to handle the combination (we already did it but it may again change)
        $paymentMethodsConditions = Mage::getSingleton('diglin_ricento/api_services_system')->getPaymentConditionsAndMethods();

        $paymentMethods = array();

        foreach ($paymentMethodsConditions as $paymentMethodsCondition)
            if (isset($paymentMethodsCondition['PaymentMethods'])) {
                foreach ($paymentMethodsCondition['PaymentMethods'] as $method) {
                    $paymentMethods[$method['PaymentMethodId']] = $method['PaymentMethodText'];
                }
            }
        return $paymentMethods;
    }

}