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
 *
 * @todo remove almost all $website and getWebsiteConfig as the configuration is not anymore on website base but default values configuration
 */
class Diglin_Ricento_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CFG_ENABLED = 'ricento/api_config/enabled';
    const CFG_ASSISTANT_URL = 'ricento/api_config/assistant_url';
    const CFG_ASSISTANT_URL_DEV = 'ricento/api_config/assistant_url_dev';
    const CFG_RICARDO_SIGNUP_API_URL = 'ricento/api_config/signup_url';
    const CFG_DEV_MODE = 'ricento/api_config/dev_mode';
    const CFG_DEBUG_MODE = 'ricento/api_config/debug';
    const CFG_API_HOST = 'ricento/api_config/host';
    const CFG_API_HOST_DEV = 'ricento/api_config/host_dev';
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

    CONST RICARDO_URL = 'http://www.ricardo.ch';

    /**
     * Is the extension enabled for the current website
     *
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isEnabled($website = 0)
    {
        return self::getWebsiteConfigFlag(self::CFG_ENABLED, $website);
    }

    /**
     * Returns product types that are available in Ricento
     *
     * @return array [ type_id => type_id ]
     */
    public function getAllowedProductTypes()
    {
        $allowProductTypes = array();
        foreach (Mage::getConfig()
                     ->getNode('global/ricento/allow_product_types')->children() as $type) {
            $allowProductTypes[$type->getName()] = $type->getName();
        }
        return $allowProductTypes;
    }

    /**
     * Get the configuration Ricardo url
     *
     * @param int|null|Mage_Core_Model_Website
     * @return string
     */
    public function getConfigurationUrl($website = null)
    {
        $params = array();
        if (!is_null($website)) {
            $params = array('website' => Mage::app()->getWebsite($website)->getCode());
        }
        return Mage::helper('adminhtml')->getUrl('adminhtml/system_config/edit/section/ricento', $params);
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
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isDevMode($website = 0)
    {
        return self::getWebsiteConfigFlag(self::CFG_DEV_MODE, $website);
    }

    /**
     * Check if Ricardo API is configured correctly
     *
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isConfigured($website = 0)
    {
        $configured = false;
        $configuredAccount = (!$this->canSimulateAuthorization() || ($this->canSimulateAuthorization() && $this->getRicardoUsername($website) && $this->getRicardoPass($website))) ? true : false;

        foreach ($this->getSupportedLang() as $lang) {
            if ($this->getPartnerId($lang, $website) && $this->getPartnerPass($lang, $website)) {
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
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isDebugEnabled($website = 0)
    {
        return self::getWebsiteConfigFlag(self::CFG_DEBUG_MODE, $website);
    }

    /**
     * Get which method to use to calculate the shipping costs
     *
     * @param int|null|Mage_Core_Model_Store $storeId
     * @return mixed
     */
    public function getShippingCalculationMethod($storeId = null)
    {
        return MAge::getStoreConfig(self::CFG_SHIPPING_CALCULATION, $storeId);
    }

    /**
     * Can simulate authorization process
     *
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function canSimulateAuthorization($website = 0)
    {
        return self::getWebsiteConfigFlag(self::CFG_SIMULATE_AUTH, $website);
    }

    /**
     * Get the Ricardo API Partner ID Configuration
     *
     * @param string|null $lang
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return string
     */
    public function getPartnerId($lang = null, $website = 0)
    {
        $lang = $this->_getLocaleCodeForApiConfig($lang);
        return self::getWebsiteConfig(self::CFG_RICARDO_PARTNERID . $lang, $website);
    }

    /**
     * Get the Ricardo API Partner Pass Configuration
     *
     * @param string|null $lang
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return string
     */
    public function getPartnerPass($lang = null, $website = 0)
    {
        $lang = $this->_getLocaleCodeForApiConfig($lang);
        return Mage::helper('core')->decrypt(self::getWebsiteConfig(self::CFG_RICARDO_PARTNERPASS . $lang, $website));
    }

    /**
     * Get the partner url to get the confirmation
     *
     * @param int $websiteId
     * @return string
     */
    public function getPartnerUrl($websiteId = 0)
    {
        return Mage::helper('adminhtml')->getUrl('ricento/api/confirmation', array('website' => (int) $websiteId));
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
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return string
     */
    public function getRicardoUsername($website = 0)
    {
        return self::getWebsiteConfig(self::CFG_RICARDO_USERNAME, $website);
    }

    /**
     * Get the Ricardo customer username
     *
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return string
     */
    public function getRicardoPass($website = 0)
    {
        return Mage::helper('core')->decrypt(self::getWebsiteConfig(self::CFG_RICARDO_PASSWORD, $website));
    }

    /**
     * Get the delay in days to notify the owner that the API credentials will expire
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return int
     */
    public function getExpirationNotificationDelay($store = 0)
    {
        return Mage::getStoreConfig(self::CFG_EXPIRATION_NOTIFICATION_DELAY, $store);
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
     * @param $path
     * @param null|int|string|MAge_Core_Model_Website $website
     * @return mixed
     */
    public static function getWebsiteConfig($path, $website = null)
    {
        return Mage::app()->getWebsite($website)->getConfig($path);
    }

    /**
     * @param $path
     * @param null|int|string|MAge_Core_Model_Website $website
     * @return mixed
     */
    public static function getWebsiteConfigFlag($path, $website = null)
    {
        $flag = strtolower(self::getWebsiteConfig($path, $website));
        if (!empty($flag) && 'false' !== $flag) {
            return true;
        } else {
            return false;
        }
    }
}