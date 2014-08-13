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

    protected $_paymentMethodsConditions = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        // @todo check to not have credit card payment methods when it's deactivated for an account
        if (empty($this->_paymentMethodsConditions)) {
            $paymentMethodsConditions = Mage::getSingleton('diglin_ricento/api_services_system')->getPaymentConditionsAndMethods();

            foreach ($paymentMethodsConditions as $paymentMethodsCondition)
                if (isset($paymentMethodsCondition['PaymentMethods'])) {
                    foreach ($paymentMethodsCondition['PaymentMethods'] as $method) {
                        $this->_paymentMethodsConditions[$method['PaymentMethodId']] = $method['PaymentMethodText'];
                    }
                }
        }

        return $this->_paymentMethodsConditions;
    }

}