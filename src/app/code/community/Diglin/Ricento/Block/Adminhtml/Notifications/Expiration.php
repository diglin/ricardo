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
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isEnabled($website = 0)
    {
        return Mage::helper('diglin_ricento')->isEnabled($website);
    }

    /**
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isApiConfigured($website = 0)
    {
        return (bool) Mage::helper('diglin_ricento')->isConfigured($website);
    }

    /**
     * @return string
     */
    public function getConfigurationUrl()
    {
        return Mage::helper('diglin_ricento')->getConfigurationUrl();
    }

    /**
     * @param string|bool|int|Mage_Core_Model_Website $website
     * @return string
     */
    public function getValidationUrl($website)
    {
        return Mage::getSingleton('diglin_ricento/api_services_security')->getValidationUrl($website);
    }

    /**
     * @param string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isApiGoExpire($website = 0)
    {
        return (bool) Mage::helper('diglin_ricento/api')->isApiTokenCredentialGoingToExpire($website);
    }

    /**
     * @param string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isApiCredentialTokenExist($website = 0)
    {
        return (bool) Mage::helper('diglin_ricento/api')->isApiTokenCredentialExists($website);
    }

    /**
     * @param int $storeId
     * @return int
     */
    public function getExpirationNotificationDelay($storeId = 0)
    {
        return (int) Mage::helper('diglin_ricento')->getExpirationNotificationDelay($storeId);
    }

    /**
     * @return array
     */
    public function getWebsiteCollection()
    {
        return Mage::app()->getWebsites();
    }
}
