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
 * Class Diglin_Ricento_Model_Sales_Validate
 */
class Diglin_Ricento_Model_Sales_Validate_Increment extends Zend_Validate_Abstract
{
    /**
     * @var array
     */
    protected $_allowedIncrementCombination = array(
        array('start_price' => array('min' => 0.05, 'max' => 9.99), 'increment' => array('min' => 0.05, 'max' => 5.00)),
        array('start_price' => array('min' => 10.00, 'max' => 49.99), 'increment' => array('min' => 0.05, 'max' => 10.00)),
        array('start_price' => array('min' => 50.00, 'max' => 99.99), 'increment' => array('min' => 0.05, 'max' => 20.00)),
        array('start_price' => array('min' => 100.00, 'max' => 499.99), 'increment' => array('min' => 0.05, 'max' => 50.00)),
        array('start_price' => array('min' => 500.00, 'max' => 999.99), 'increment' => array('min' => 0.05, 'max' => 100.00)),
        array('start_price' => array('min' => 1000.00, 'max' => 1999.99), 'increment' => array('min' => 0.05, 'max' => 200.00)),
        array('start_price' => array('min' => 2000.00, 'max' => 4999.99), 'increment' => array('min' => 0.05, 'max' => 500.00)),
        array('start_price' => array('min' => 5000.00, 'max' => 2000000.00), 'increment' => array('min' => 0.05, 'max' => 1000.00)),
    );

    public function isValid($increment, $startPrice = 0.05)
    {
        foreach ($this->_getAllowedIncrementCombination() as $combination) {
            $startPriceValidate = new Zend_Validate_Between(array('min' => $combination['start_price']['min'], 'max' => $combination['start_price']['max']));
            if ($startPriceValidate->isValid($startPrice)) {
                $incrementValidate = new Zend_Validate_Between(array('min' => $combination['increment']['min'], 'max' => $combination['increment']['max']));
                if ($incrementValidate->isValid($increment)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return array
     */
    protected function _getAllowedIncrementCombination()
    {
        return $this->_allowedIncrementCombination;
    }
}