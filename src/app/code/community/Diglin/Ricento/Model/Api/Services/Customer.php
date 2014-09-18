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
 * Class Diglin_Ricento_Model_Api_Services_Customer
 */
class Diglin_Ricento_Model_Api_Services_Customer extends Diglin_Ricento_Model_Api_Services_Abstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'customer';

    /**
     * @var string
     */
    protected $_model = '\Diglin\Ricardo\Managers\Customer';

    /**
     * Overwritten just to get the class/method auto completion
     * Be aware that using directly this method to use the methods of the object instead of using
     * the magic methods of the abstract (__call, __get, __set) will prevent to use the cache of Magento
     *
     * @return \Diglin\Ricardo\Managers\Customer
     */
    public function getServiceModel()
    {
        return parent::getServiceModel();
    }
}