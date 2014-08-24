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
 * @method Diglin_Ricento_Model_Rule setShippingPackage(int $shippingPackage)
 * @method Diglin_Ricento_Model_Rule setShippingCumulativeFee(int $shippingCumulativeFee)
 * @method string getShippingMethod()
 * @method string getShippingDescription()
 * @method float getShippingPrice()
 * @method string getShippingAvailaibility()
 * @method string getPaymentDescription()
 * @method string[] getPaymentMethods()
 * @method string getShippingPackage()
 * @method int getShippingCumulativeFee()
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
     * Set date of last update, convert payment method array to string
     *
     * @return Diglin_Ricento_Model_Rule
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        $this->_preparePaymentMethods(false);

        return $this;
    }

    /**
     * Convert payment method string to array
     *
     * @return Diglin_Ricento_Model_Rule
     */
    protected function _afterLoad()
    {
        $this->_preparePaymentMethods();

        return parent::_afterLoad();
    }

    /**
     * Convert payment method string to array
     *
     * @return Diglin_Ricento_Model_Rule
     */
    protected function _afterSave()
    {
        $this->_preparePaymentMethods();

        return parent::_afterSave();
    }

    /**
     * Prepare Payment Methods
     */
    protected function _preparePaymentMethods($explode = true)
    {
        if ($explode){
            if (isset($this->_data['payment_methods']) && !is_null($this->_data['payment_methods'])) {
                $this->_data['payment_methods'] = explode(',', $this->_data['payment_methods']);
            } else {
                $this->_data['payment_methods'] = array();
            }
        } else {
            if (isset($this->_data['payment_methods']) && is_array($this->_data['payment_methods'])) {
                $this->_data['payment_methods'] = implode(',', $this->_data['payment_methods']);
            } else {
                $this->_data['payment_methods'] = array();
            }
        }
        return $this;
    }
}