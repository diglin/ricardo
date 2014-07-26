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
 * Class Diglin_Ricento_Helper_Data
 */
class Diglin_Ricento_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CFG_ASSISTANT_URL = 'ricento/config/assistant_url';
    const CFG_ASSISTANT_URL_DEV = 'ricento/config/assistant_url_dev';
    const CFG_RICARDO_SIGNUP_API_URL = 'ricento/config/signup_url';
    const CFG_DEV_MODE = 'ricento/config/dev_mode';
    const CFG_DEBUG_MODE = 'ricento/config/debug';
    const CFG_SIMULATE_AUTH = 'ricento/config/simulate_authorization';
    const CFG_SHIPPING_CALCULATION = 'ricento/global/shipping_calculation';

    const STATUS_PENDING = 'pending';
    const STATUS_LISTED = 'listed';
    const STATUS_STOPPED = 'stopped';
    const STATUS_ERROR = 'error';

    const SUPPORTED_LANG_DE = 'de';
    const SUPPORTED_LANG_FR = 'fr';

    /**
     * Returns product types that are available in Ricento
     *
     * @return array [ type_id => type_id ]
     */
    public function getAllowedProductTypes()
    {
        return array(
            'simple'       => 'simple',
            'configurable' => 'configurable',
            'grouped'      => 'grouped');
    }
    /**
     * Get the Ricardo Assistant Url
     *
     * @return string
     */
    public function getRicardoAssistantUrl()
    {
        if ($this->isDevMode()) {
            $urlConfig = self::CFG_ASSISTANT_URL_DEV;
        } else {
            $urlConfig = self::CFG_ASSISTANT_URL;
        }

        return Mage::getStoreConfig($urlConfig);
    }

    /**
     * Get the Ricardo Signup Form URL
     *
     * @param boolean $intern
     * @return string
     */
    public function getRicardoSignupApiUrl($intern = true)
    {
        if ($intern) {
            return Mage::helper('adminhtml')->getUrl('ricento/account/signup');
        }
        return Mage::getStoreConfig(self::CFG_RICARDO_SIGNUP_API_URL);
    }

    /**
     * Is Development Mode enabled
     *
     * @return bool
     */
    public function isDevMode()
    {
        return Mage::getStoreConfigFlag(self::CFG_DEV_MODE);
    }

    public function isConfigured()
    {
        return ((($this->getPartnerId(self::SUPPORTED_LANG_DE) && $this->getPartnerPass(self::SUPPORTED_LANG_DE))
            || ($this->getPartnerId(self::SUPPORTED_LANG_FR) && $this->getPartnerPass(self::SUPPORTED_LANG_FR)))
            && ($this->getRicardoUsername() && $this->getRicardoPass()));
    }

    /**
     * Is Debug Enabled
     *
     * @return bool
     */
    public function isDebugEnabled()
    {
        return Mage::getStoreConfigFlag(self::CFG_DEBUG_MODE);
    }

    /**
     * Get which method to use to calculate the shipping costs
     *
     * @return mixed
     */
    public function getShippingCalculationMethod()
    {
        return MAge::getStoreConfig(self::CFG_SHIPPING_CALCULATION);
    }

    /**
     * Can simulate authorization process
     *
     * @return bool
     */
    public function canSimulateAuthorization()
    {
        return Mage::getStoreConfigFlag(self::CFG_SIMULATE_AUTH);
    }

    /**
     * Get the Ricardo API Partner ID Configuration
     *
     * @param string|null $locale
     * @param int|null|Mage_Core_Model_Store $storeId
     * @return string
     */
    public function getPartnerId($locale = null, $storeId = null)
    {
        $locale = $this->_getLocaleCodeForApiConfig($locale);
        return Mage::getStoreConfig('ricento/config/partner_id_' . $locale, $storeId);
    }

    /**
     * Get the Ricardo API Partner Pass Configuration
     *
     * @param string|null $locale
     * @param int|null|Mage_Core_Model_Store $storeId
     * @return string
     */
    public function getPartnerPass($locale = null, $storeId = null)
    {
        $locale = $this->_getLocaleCodeForApiConfig($locale);
        return Mage::getStoreConfig('ricento/config/partner_pass_' . $locale, $storeId);
    }

    /**
     * Normalize the locale to get only two first letters code or the Germand default
     *
     * @param string $locale
     * @return string
     */
    protected function _getLocaleCodeForApiConfig($locale = null)
    {
        if (empty($locale)) {
            $locale = Mage::app()->getLocale()->getLocaleCode();
        }

        if ($locale) {
            $locale = substr(strtolower($locale), 0, 2);
        }

        if ($locale != self::SUPPORTED_LANG_DE && $locale != self::SUPPORTED_LANG_FR) {
            $locale = self::SUPPORTED_LANG_DE;
        }

        return $locale;
    }

    /**
     * Get the Ricardo customer username
     *
     * @param null $storeId
     * @return string
     */
    public function getRicardoUsername($storeId = null)
    {
        return Mage::getStoreConfig('ricento/config/ricardo_username', $storeId);
    }

    /**
     * Get the Ricardo customer username
     *
     * @param null $storeId
     * @return string
     */
    public function getRicardoPass($storeId = null)
    {
        return Mage::getStoreConfig('ricento/config/ricardo_password', $storeId);
    }

    /**
     * Disable all elements in a form recursively
     *
     * @param Varien_Data_Form_Abstract $form
     */
    public function disableForm(Varien_Data_Form_Abstract $form)
    {
        foreach ($form->getElements() as $element) {
            /* @var $element Varien_Data_Form_Element_Abstract */
            $element->setDisabled(true);
            if ($element->getType() === 'button') {
                $element->addClass('disabled');
            }
            $this->disableForm($element);
        }
    }

    /**
     * Returns array of categories as returned from Ricardo API
     *
     * @return mixed
     */
    public function getRicardoCategoriesFromApi()
    {
        //TODO real implementation, should return the array from cache
        return unserialize(file_get_contents(Mage::getModuleDir('etc', 'Diglin_Ricento') . DS . 'categories_ricardo.txt'));
    }
}