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
    protected $_paymentMethodsConditions = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_paymentMethodsConditions)) {

            $creditCardAvailable = Mage::helper('diglin_ricento')->isCardPaymentAllowed();

            $paymentMethodsConditions = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getPaymentConditionsAndMethods();

            foreach ($paymentMethodsConditions as $paymentMethodsCondition)
                if (isset($paymentMethodsCondition['PaymentMethods'])) {
                    foreach ($paymentMethodsCondition['PaymentMethods'] as $method) {
                        if (!$creditCardAvailable && $method['PaymentMethodId'] == \Diglin\Ricardo\Enums\PaymentMethods::TYPE_CREDIT_CARD) {
                            continue;
                        }
                        $this->_paymentMethodsConditions[$method['PaymentMethodId']] = $method['PaymentMethodText'];
                    }
                }
        }

        return $this->_paymentMethodsConditions;
    }

}