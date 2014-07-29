<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Test_Model_Rule_Validate
 */
class Diglin_Ricento_Test_Model_Rule_Validate extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var Diglin_Ricento_Model_Rule_Validate
     */
    protected $_subject;

    protected function setUp()
    {
        $this->_subject = Mage::getModel('diglin_ricento/rule_validate');
        return parent::setUp();
    }

    /**
     * @test
     * @loadExpectations
     * @dataProvider dataProvider
     */
    public function testPaymentMethodCombinations($payment, $shipping)
    {
        $expectedResults = $this->expected(sprintf('payment-%s_shipping-%s', join('-', $payment), $shipping));
        $this->assertEquals(
            $expectedResults->getIsValid(),
            $this->_subject->isValid(array('shipping' => $shipping, 'payment' => $payment)),
            'isValid() result'
        );
        $this->assertEquals(
            $expectedResults->getMessages(),
            array_keys($this->_subject->getMessages()),
            'keys of getMessages() result',
            0, 10, true
        );
    }
}