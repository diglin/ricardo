<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

use \Diglin\Ricardo\Managers\Security;
use \Diglin\Ricardo\Exceptions\SecurityErrors;

class Diglin_Ricento_Model_Api_Services_Security extends Diglin_Ricento_Model_Api_Services_Abstract
{
    /**
     * @var Security
     */
    protected $_securityServiceModel;

    /**
     * @return Security
     */
    public function getSecurityServiceModel()
    {
        if (!$this->_securityServiceModel) {
            $object = $this->getServiceManager()->getSecurityManager();
            $this->_securityServiceModel = Mage::objects()->save($object);
        }
        return Mage::objects()->load($this->_securityServiceModel);
    }

    /**
     * Get the token credential used to be saved in DB
     *
     * @return bool|string
     */
    public function getTokenCredential()
    {
        try {
            return $this->getSecurityServiceModel()->getTokenCredential();
        } catch (SecurityErrors $e) {
            if ($e->getCode() == 11) {

            }

        }
        return false;
    }

    /**
     * Get the validation Url necessary if simulation of authorization process is not allowed
     *
     * @return string
     */
    public function getValidationUrl()
    {
        // it will initialize the validation url
        $this->getSecurityServiceModel()->getTemporaryToken();
        return $this->getSecurityServiceModel()->getValidationUrl();
    }
}