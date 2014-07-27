<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Model_Rule
 *
 * @method Diglin_Ricento_Model_Rule setShippingMethod(string $shippingMethod)
 * @method Diglin_Ricento_Model_Rule setShippingDescription(string $shippingDescription)
 * @method Diglin_Ricento_Model_Rule setShippingPrice(string $shippingPrice)
 * @method Diglin_Ricento_Model_Rule setShippingAvailability(string $shippingAvailability)
 * @method Diglin_Ricento_Model_Rule setPaymentMethods(string $paymentMethods)
 * @method Diglin_Ricento_Model_Rule setPaymentDescription(string $paymentDescription)
 * @method string getShippingMethod()
 * @method string getShippingDescription()
 * @method float getShippingPrice()
 * @method string getShippingAvailaibility()
 * @method string getPaymentDescription()
 *
 */
class Diglin_Ricento_Model_Rule extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'shipping_payment_rule';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'shipping_payment_rule';

    protected function _construct()
    {
        $this->_init('diglin_ricento/rule');
    }

    /**
     * Set date of last update
     *
     * @return Diglin_Ricento_Model_Rule
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethods()
    {
        if ($this->_data['payment_methods']) {
            $this->_data['payment_methods'] = explode(',', $this->_data['payment_methods']);
        }
        return $this->_data['payment_methods'];
    }
}