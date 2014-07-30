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
     * Define if the token credential is going to expire
     *
     * @return bool
     */
    public function isApiTokenCredentialGoingToExpire()
    {
        $identifiedToken = Mage::getResourceModel('diglin_ricento/api_token')->getSpecificTokenType(Security::TOKEN_TYPE_IDENTIFIED);
        $dayDelay = Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_EXPIRATION_NOTIFICATION_DELAY);

        if (isset($identifiedToken['expiration_date'])
            && time() >= Mage::getSingleton('core/date')->timestamp($identifiedToken['expiration_date']) - ($dayDelay * 24 * 3600)) {
            return true;
        };

        return false;
    }
}