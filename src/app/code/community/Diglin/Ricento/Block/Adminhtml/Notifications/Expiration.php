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
 * Class Diglin_Ricento_Block_Adminhtml_Notifications
 */
class Diglin_Ricento_Block_Adminhtml_Notifications_Expiration extends Diglin_Ricento_Block_Adminhtml_Notifications_Default
{
    protected $_template = 'ricento/notifications/expiration.phtml';

    /**
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return Mage::helper('diglin_ricento')->isEnabled($store);
    }

    /**
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function isApiConfigured($store = null)
    {
        return (bool) Mage::helper('diglin_ricento')->isConfigured($store);
    }

    /**
     * @return string
     */
    public function getConfigurationUrl()
    {
        return Mage::helper('diglin_ricento')->getConfigurationUrl();
    }

    /**
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    public function getValidationUrl(Mage_Core_Model_Store $store)
    {
        return Mage::getSingleton('diglin_ricento/api_services_security')->getValidationUrl($store);
    }

    /**
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function isApiGoExpire($store = null)
    {
        return (bool) Mage::helper('diglin_ricento/api')->isApiTokenCredentialGoingToExpire($store);
    }

    /**
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function isApiCredentialTokenExist($store = null)
    {
        return (bool) Mage::helper('diglin_ricento/api')->isApiTokenCredentialExists($store);
    }

    /**
     * @return int
     */
    public function getExpirationNotificationDelay()
    {
        return (int) Mage::helper('diglin_ricento')->getExpirationNotificationDelay();
    }

    /**
     * @return array
     */
    public function getWebsiteCollection()
    {
        return Mage::app()->getWebsites();
    }
}
