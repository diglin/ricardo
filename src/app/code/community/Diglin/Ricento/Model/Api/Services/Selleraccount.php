<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Model_Api_Services_SellerAccount extends Diglin_Ricento_Model_Api_Services_Abstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'seller_account';

    /**
     * @var string
     */
    protected $_model = '\Diglin\Ricardo\Managers\SellerAccount';

    /**
     * Overwritten just to get the class/method auto completion
     *
     * Be aware that using directly this method to use the methods of the object instead of using
     * the magic methods of the abstract (__call, __get, __set) will prevent to use the cache of Magento
     *
     * @return \Diglin\Ricardo\Managers\SellerAccount
     */
    public function getServiceModel()
    {
        return parent::getServiceModel();
    }
} 