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
        $token = Mage::getModel('diglin_ricento/api_token')
            ->loadByWebsiteAndTokenType(Security::TOKEN_TYPE_IDENTIFIED, Mage::app()->getWebsite($website)->getId());

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
        $token = Mage::getModel('diglin_ricento/api_token')
            ->loadByWebsiteAndTokenType(Security::TOKEN_TYPE_IDENTIFIED, Mage::app()->getWebsite($website)->getId());
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

    /**
     * Calculate the session start time
     *
     * @param int $sessionDuration in minutes
     * @param null $time
     * @return int|null
     */
    public function calculateSessionStart($sessionDuration, $time)
    {
        return strtotime($time) - ($sessionDuration * 60);
    }

    /**
     * @param int $websiteId
     * @return string
     */
    public function getValidationUrl($websiteId = 0)
    {
        return Mage::getSingleton('diglin_ricento/api_services_security')
//@fixme there is issue with getting credential token in multi shop so for real website support start to fix here - not planned at the moment
//            ->setCurrentWebsite($websiteId)
            ->getValidationUrl();
    }
}