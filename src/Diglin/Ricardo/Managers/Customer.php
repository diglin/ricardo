<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Managers;

class Customer extends ManagerAbstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'customer';

    /**
     * @var array
     */
    protected $_currentCustomer;

    /**
     * @return array
     */
    public function getCustomerInformation()
    {
        if (empty($this->_currentCustomer)) {
            $this->_currentCustomer = $this->_proceed('CustomerInformation');
        }

        return $this->_currentCustomer;
    }
}