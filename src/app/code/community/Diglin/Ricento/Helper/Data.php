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
    const CFG_ASSISTANT_URL = 'ricento/api_config/assistant_url';
    const CFG_ASSISTANT_URL_DEV = 'ricento/api_config/assistant_url_dev';
    const CFG_RICARDO_SIGNUP_API_URL = 'ricento/api_config/signup_url';
    const CFG_DEV_MODE = 'ricento/api_config/dev_mode';
    const CFG_DEBUG_MODE = 'ricento/api_config/debug';
    const CFG_SIMULATE_AUTH = 'ricento/api_config/simulate_authorization';
    const CFG_RICARDO_USERNAME = 'ricento/api_config/ricardo_username';
    const CFG_RICARDO_PASSWORD = 'ricento/api_config/ricardo_password';
    const CFG_RICARDO_PARTNERID = 'ricento/api_config/partner_id_';
    const CFG_RICARDO_PARTNERPASS = 'ricento/api_config/partner_pass_';
    const CFG_EXPIRATION_NOTIFICATION_DELAY = 'ricento/api_config/expiration_notification_delay'; // in day
    const CFG_SUPPORTED_LANG = 'ricento/api_config/lang';
    const DEFAULT_SUPPORTED_LANG = 'de';

    const CFG_SHIPPING_CALCULATION = 'ricento/global/shipping_calculation';

    const STATUS_PENDING = 'pending';
    const STATUS_LISTED = 'listed';
    const STATUS_STOPPED = 'stopped';
    const STATUS_ERROR = 'error';

    const LOG_FILE = 'ricento.log';

    const ALLOWED_CURRENCY = 'CHF';

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
     * Get the configuration Ricardo url
     *
     * @return string
     */
    public function getConfigurationUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/system_config/edit/section/ricento');
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

    /**
     * Check if Ricardo API is configured correctly
     *
     * @return bool
     */
    public function isConfigured()
    {
        $configured = false;
        $configuredAccount = (!$this->canSimulateAuthorization() || ($this->canSimulateAuthorization() && $this->getRicardoUsername() && $this->getRicardoPass())) ? true : false;

        foreach ($this->getSupportedLang() as $lang) {
            if ($this->getPartnerId($lang) && $this->getPartnerPass($lang)) {
                $configured = true;
                break;
            }
        }

        if ($configured && $configuredAccount) {
            return true;
        }

        return false;
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
     * @param string|null $lang
     * @param int|null|Mage_Core_Model_Store $storeId
     * @return string
     */
    public function getPartnerId($lang = null, $storeId = null)
    {
        $lang = $this->_getLocaleCodeForApiConfig($lang);
        return Mage::getStoreConfig(self::CFG_RICARDO_PARTNERID . $lang, $storeId);
    }

    /**
     * Get the Ricardo API Partner Pass Configuration
     *
     * @param string|null $lang
     * @param int|null|Mage_Core_Model_Store $storeId
     * @return string
     */
    public function getPartnerPass($lang = null, $storeId = null)
    {
        $lang = $this->_getLocaleCodeForApiConfig($lang);
        return Mage::helper('core')->decrypt(Mage::getStoreConfig(self::CFG_RICARDO_PARTNERPASS . $lang, $storeId));
    }

    /**
     * Normalize the locale to get only two first letters code or the Germand default
     *
     * @param string $lang
     * @return string
     */
    protected function _getLocaleCodeForApiConfig($lang = null)
    {
        if (empty($lang)) {
            $lang = Mage::app()->getLocale()->getLocaleCode();
        }

        if ($lang) {
            $lang = substr(strtolower($lang), 0, 2);
        }

        if (in_array($lang, $this->getSupportedLang())) {
            $lang = 'de';
        }

        return $lang;
    }

    /**
     * Get the list of supported API language
     *
     * @return array
     */
    public function getSupportedLang()
    {
        return explode(',', strtolower(Mage::getStoreConfig(self::CFG_SUPPORTED_LANG)));
    }

    /**
     * Get the Ricardo customer username
     *
     * @param null $storeId
     * @return string
     */
    public function getRicardoUsername($storeId = null)
    {
        return Mage::getStoreConfig(self::CFG_RICARDO_USERNAME, $storeId);
    }

    /**
     * Get the Ricardo customer username
     *
     * @param null $storeId
     * @return string
     */
    public function getRicardoPass($storeId = null)
    {
        return Mage::helper('core')->decrypt(Mage::getStoreConfig(self::CFG_RICARDO_PASSWORD, $storeId));
    }

    /**
     * Get the delay in days to notify the owner that the API credentials will expire
     *
     * @return int
     */
    public function getExpirationNotificationDelay()
    {
        return Mage::getStoreConfig(self::CFG_EXPIRATION_NOTIFICATION_DELAY);
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