<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Diglin\Ricardo\Managers;

/**
 * Class Customer
 * @package Diglin\Ricardo\Managers
 */
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
