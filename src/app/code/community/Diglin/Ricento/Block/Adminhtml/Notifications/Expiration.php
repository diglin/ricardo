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
 * Class Diglin_Ricento_Block_Adminhtml_Notifications
 */
class Diglin_Ricento_Block_Adminhtml_Notifications_Expiration extends Diglin_Ricento_Block_Adminhtml_Notifications_Default
{
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
     * @param string|bool|int|Mage_Core_Model_Website $website
     * @return string
     */
    public function getValidationUrl($website)
    {
        return Mage::getSingleton('diglin_ricento/api_services_security')
            ->setCurrentWebsite($website)
            ->getValidationUrl();
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
