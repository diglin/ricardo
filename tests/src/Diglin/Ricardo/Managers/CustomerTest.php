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

use Diglin\Ricardo\Core\Helper;

class CustomerTest extends TestAbstract
{
    /**
     * @var Customer
     */
    protected $_customerManager;

    protected function setUp()
    {
        $this->_customerManager = new Customer($this->getServiceManager());
        parent::setUp();
    }

    public function testCustomerInformation()
    {
        $customer = $this->_customerManager->getCustomerInformation();

        $this->outputContent($customer, 'Customer Information: ', true);
    }
}
