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
 * Class Diglin_Ricento_Model_Config_Source_Sales_Type
 */
class Diglin_Ricento_Model_Config_Source_Sales_Type extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const AUCTION = 'auction';
    const BUYNOW = 'buynow';

    /**
     * @return array
     */
    public function toOptionHash()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            '' => $helper->__('-- Please Select --'),
            self::AUCTION  => $helper->__('Auction'),
            self::BUYNOW => $helper->__('Buy now')
        );
    }
}