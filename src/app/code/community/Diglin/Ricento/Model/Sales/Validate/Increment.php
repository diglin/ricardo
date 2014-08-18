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
    protected $_allowedCombinations = array(
        array('start_price' => array('min' => 0.05, 'max' => 9.99), 'increment' => array('min' => 0.05, 'max' => 5.00)),
        array('start_price' => array('min' => 10.00, 'max' => 49.99), 'increment' => array('min' => 0.05, 'max' => 10.00)),
        array('start_price' => array('min' => 50.00, 'max' => 99.99), 'increment' => array('min' => 0.05, 'max' => 20.00)),
        array('start_price' => array('min' => 100.00, 'max' => 499.99), 'increment' => array('min' => 0.05, 'max' => 50.00)),
        array('start_price' => array('min' => 500.00, 'max' => 999.99), 'increment' => array('min' => 0.05, 'max' => 100.00)),
        array('start_price' => array('min' => 1000.00, 'max' => 1999.99), 'increment' => array('min' => 0.05, 'max' => 200.00)),
        array('start_price' => array('min' => 2000.00, 'max' => 4999.99), 'increment' => array('min' => 0.05, 'max' => 500.00)),
        array('start_price' => array('min' => 5000.00, 'max' => 1000000.00), 'increment' => array('min' => 0.05, 'max' => 1000.00)),
    );

    /**
     * @param int|float $increment
     * @param float $startPrice
     * @return bool
     */
    public function isValid($increment, $startPrice = 0.05)
    {
        foreach ($this->_getAllowedCombination() as $combination) {
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
     * Get an array of allowed combination
     *
     * @return array
     */
    protected function _getAllowedCombination()
    {
        return $this->_allowedCombinations;
    }

    /**
     * Get Json string of the allowed combination
     *
     * @return string
     */
    protected function _getJsonAllowedCombination()
    {
        return json_encode($this->_allowedCombinations);
    }

    /**
     * Get the Validator Javascript code
     *
     * @return string
     */
    public function getJavaScriptValidator()
    {
        $block = Mage::getBlockSingleton('adminhtml/template');
        $block->setTemplate('ricento/js/salesoptions/validate/increment.phtml')
            ->setJsonAllowedCombinations($this->_getJsonAllowedCombination())
            ->setErrorMessage($this->getErrorMessage());

        return $block->toHtml();
    }

    /**
     * @param bool $wrapNotice
     * @return string
     */
    public function getErrorMessage($wrapNotice = true)
    {
        $helper = Mage::helper('diglin_ricento');

        return $helper->__('This increment value is not possible for this start price.') .
            ($wrapNotice ? '<ul class="messages"><li class="notice-msg">' : ' ') .
            $helper->__('Only the following combinations are possible:') .
            $this->_htmlListOfAllowedCombinations() .
            ($wrapNotice ? '</li></ul>' : '');
    }

    protected function _htmlListOfAllowedCombinations()
    {
        $helper = Mage::helper('diglin_ricento');

        $html = '<table class="allowed-combinations">';
        $html .= '<thead><tr>';
        $html .= '<th><span>'. $helper->__('Start price is between') . '</span></th>';
        $html .= '<th><span>'. $helper->__('Increment must be between') .'</span></th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        foreach ($this->_getAllowedCombination() as $combination) {
            $html .= '<tr>';
            $html .= '<td><span>' . $combination['start_price']['min'] . '...' . $combination['start_price']['max'] . '</span></td>';
            $html .= '<td><span>' . $combination['increment']['min'] . '...' . $combination['increment']['max'] . '</span></td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
}