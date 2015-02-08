<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Test_Model_Rule_Validate
 */
class Diglin_Ricento_Test_Model_Rule_Validate extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var Diglin_Ricento_Model_Sales_Validate_Increment
     */
    protected $_subject;

    protected function setUp()
    {
        $this->_subject = Mage::getModel('diglin_ricento/validate_sales_increment');
        return parent::setUp();
    }

    /**
     * @test
     * @loadExpectations
     * @dataProvider dataProvider
     */
    public function testStartPriceIncrementCombination($startPrice, $increment)
    {
        $expectedResults = $this->expected(sprintf('startprice-%s_increment-%s', $startPrice, $increment));
        $this->assertEquals(
            $expectedResults->getIsValid(),
            $this->_subject->isValid($increment, $startPrice),
            'isValid() result'
        );
//        $this->assertEquals(
//            $expectedResults->getMessages(),
//            array_keys($this->_subject->getMessages()),
//            'keys of getMessages() result',
//            0, 10, true
//        );
    }
}