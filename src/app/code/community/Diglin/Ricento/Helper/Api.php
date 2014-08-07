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
     * Cache type for ricardo API
     */
    const CACHE_TYPE        = 'ricardo_api';

    /**
     * Cache tag for ricardo API
     */
    const CACHE_TAG         = 'RICARDO_API';

    /**
     * Cache lifetime
     */
    const CACHE_LIFETIME    = 86400;

    /**
     * Get if the token credential is going to expire or even not exist
     *
     * @param int|string|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isApiTokenCredentialGoingToExpire($website = 0)
    {
        $token = Mage::getModel('diglin_ricento/api_token')->loadByWebsiteAndTokenType(Security::TOKEN_TYPE_IDENTIFIED, Mage::app()->getWebsite($website)->getId());
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
     * @param int|string|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isApiTokenCredentialExists($website = 0)
    {
        $token = Mage::getModel('diglin_ricento/api_token')->loadByWebsiteAndTokenType(Security::TOKEN_TYPE_IDENTIFIED, Mage::app()->getWebsite($website)->getId());
        return ($token->getId()) ? true : false;
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