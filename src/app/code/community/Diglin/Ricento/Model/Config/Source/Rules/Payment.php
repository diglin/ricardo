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

use \Diglin\Ricardo\Enums\PaymentMethods;

/**
 * Class Diglin_Ricento_Model_Config_Source_Rules_Payment
 */
class Diglin_Ricento_Model_Config_Source_Rules_Payment extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @var array
     */
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
                        if (!$creditCardAvailable && $method['PaymentMethodId'] == PaymentMethods::TYPE_CREDIT_CARD) {
                            continue;
                        }
                        $this->_paymentMethodsConditions[$method['PaymentMethodId']] = $method['PaymentMethodText'];
                    }
                }
        }

        return $this->_paymentMethodsConditions;
    }

}