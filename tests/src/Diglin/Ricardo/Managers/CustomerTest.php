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
