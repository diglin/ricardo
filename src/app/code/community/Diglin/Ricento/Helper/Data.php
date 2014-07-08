<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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

    /**
     * Get the Ricardo Assistant Url
     *
     * @return string
     */
    public function getRicardoAssistantUrl()
    {
        if ($this->isDevMode()) {
            return Mage::getStoreConfig(self::CFG_ASSISTANT_URL_DEV);
        } else {
            return Mage::getStoreConfig(self::CFG_ASSISTANT_URL);
        }
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
}