<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

use \Diglin\Ricardo\Services\Security;

/**
 * Class Diglin_Ricento_Helper_Api
 */
class Diglin_Ricento_Helper_Api extends Mage_Core_Helper_Abstract
{
    /**
     * Get if the token credential is going to expire or even not exist
     *
     * @param int|string|null|Mage_Core_Model_Store $store
     * @return bool
     */
    public function isApiTokenCredentialGoingToExpire($store = null)
    {
        $websiteId = $this->_getWebsiteId($store);

        $token = Mage::getModel('diglin_ricento/api_token')->loadByWebsiteAndTokenType(Security::TOKEN_TYPE_IDENTIFIED, $websiteId);
        $expirationDate = $token->getExpirationDate();
        $dayDelay = Mage::helper('diglin_ricento')->getExpirationNotificationDelay();

        if (empty($expirationDate) ||
            isset($expirationDate) && time() >= (Mage::getSingleton('core/date')->timestamp($expirationDate) - ($dayDelay * 24 * 3600)))
        {
            return true;
        }

        return false;
    }

    /**
     * Get if the token credential exists
     *
     * @param int|string|null|Mage_Core_Model_Store $store
     * @return bool
     */
    public function isApiTokenCredentialExists($store = null)
    {
        $token = Mage::getModel('diglin_ricento/api_token')->loadByWebsiteAndTokenType(Security::TOKEN_TYPE_IDENTIFIED, $this->_getWebsiteId($store));
        return ($token->getId()) ? true : false;
    }

    /**
     * @param int|string|null|Mage_Core_Model_Store $store
     * @return int|null|string
     */
    protected function _getWebsiteId ($store)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $websiteId = $store->getWebsiteId();
        } else if (is_numeric($store)) {
            $websiteId = Mage::app()->getStore($store)->getWebsiteId();
        } else {
            $websiteId = Mage::app()->getStore()->getWebsiteId();
        }
        return $websiteId;
    }

    /**
     * @param int $sessionDuration in minutes
     * @param null $time
     * @return int|null
     */
    public function calculateSessionExpirationDate($sessionDuration, $time = null)
    {
        $sessionDuration *= 60;

        if (is_null($time)) {
            return time() + $sessionDuration;
        }

        return $time + $sessionDuration;
    }
}