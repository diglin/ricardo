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

class Diglin_Ricento_Model_Api_Services extends Diglin_Ricento_Model_Api_Services_Abstract
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
            $object = new Security($this->getServiceManager());
            $this->_securityServiceModel = Mage::objects()->save($object);
        }
        return Mage::objects()->load($this->_securityServiceModel);
    }
}