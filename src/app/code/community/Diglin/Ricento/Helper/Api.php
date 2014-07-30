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
    public function isApiTokenCredentialGoingToExpire()
    {
        // @todo to implement the logic with the API
        $identifiedToken = Mage::getResourceModel('diglin_ricento/api_token')->getSpecificTokenType(Security::TOKEN_TYPE_IDENTIFIED);



        return true;
    }
}