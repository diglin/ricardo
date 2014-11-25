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
    protected $_apiReady;

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

    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        //@todo if support multi websites needed, find an other logic here
        $website = Mage::app()->getWebsite();
        try {
            if ($this->isEnabled($website) && $this->isApiConfigured($website) && $this->isApiGoExpire($website)) {
                $this->setApiReady(true);
            } else {
                $this->setApiReady(false);
            }
        } catch (Exception $e) {
            $this->setApiReady(false);
            if ($this->isEnabled($website) && $this->isApiConfigured()) {
                Mage::log($e->__toString(), Diglin_Ricento_Helper_Data::LOG_FILE);
                Mage::getSingleton('adminhtml/session')->addError($this->__('Error occurred with the API. Check if the API is correctly configured: %s', $e->__toString()));
            }
        }

        return parent::_beforeToHtml();
    }

    /**
     * @param mixed $apiReady
     * @return $this
     */
    public function setApiReady($apiReady)
    {
        $this->_apiReady = (bool) $apiReady;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiReady()
    {
        return (bool) $this->_apiReady;
    }
}
