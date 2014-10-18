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
 * Class Diglin_Ricento_Model_Rule
 *
 * @method Diglin_Ricento_Model_Rule setShippingMethod(string $shippingMethod)
 * @method Diglin_Ricento_Model_Rule setShippingDescriptionDe(string $shippingDescriptionDe)
 * @method Diglin_Ricento_Model_Rule setShippingDescriptionFr(string $shippingDescriptionFr)
 * @method Diglin_Ricento_Model_Rule setShippingPrice(string $shippingPrice)
 * @method Diglin_Ricento_Model_Rule setShippingAvailability(string $shippingAvailability)
 * @method Diglin_Ricento_Model_Rule setPaymentMethods(string $paymentMethods)
 * @method Diglin_Ricento_Model_Rule setPaymentDescriptionDe(string $paymentDescriptionDe)
 * @method Diglin_Ricento_Model_Rule setPaymentDescriptionFr(string $paymentDescriptionFr)
 * @method Diglin_Ricento_Model_Rule setShippingPackage(int $shippingPackage)
 * @method Diglin_Ricento_Model_Rule setShippingCumulativeFee(int $shippingCumulativeFee)
 * @method string getShippingMethod()
 * @method string getShippingDescriptionDe()
 * @method string getShippingDescriptionFr()
 * @method float getShippingPrice()
 * @method string getShippingAvailability()
 * @method string getPaymentDescriptionDe()
 * @method string getPaymentDescriptionFr()
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
                $this->_data['payment_methods'] = '';
            }
        }
        return $this;
    }

    /**
     * @param string $lang
     * @return string
     */
    public function getShippingDescription($lang = Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG)
    {
        return $this->_getDescriptionLangMethod($lang, 'getShippingDescription');
    }

    /**
     * @param string $lang
     * @return string
     */
    public function getPaymentDescription($lang = Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG)
    {
        return $this->_getDescriptionLangMethod($lang, 'getPaymentDescription');
    }

    /**
     * @param string $lang
     * @param string $method
     * @return string
     */
    protected function _getDescriptionLangMethod($lang = Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG, $method)
    {
        $supportedLang = Mage::helper('diglin_ricento')->getSupportedLang();
        $lang = strtolower($lang);

        if (in_array($lang, $supportedLang)) {
            $method = $method . ucwords($lang);
            return $this->$method();
        }
        return '';
    }
}