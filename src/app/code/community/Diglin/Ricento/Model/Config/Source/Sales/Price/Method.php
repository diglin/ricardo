<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Sales_Price_Method
 */
class Diglin_Ricento_Model_Config_Source_Sales_Price_Method extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const PRICE_TYPE_NOCHANGE = 0;

    const PRICE_TYPE_FIXED_POS = 1;

    const PRICE_TYPE_FIXED_NEG = 2;

    const PRICE_TYPE_DYNAMIC_POS = 3;

    const PRICE_TYPE_DYNAMIC_NEG = 4;

    /**
     * @return array
     */
    public function toOptionHash()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            '' => $helper->__('-- Select Method --'),
            self::PRICE_TYPE_NOCHANGE => $helper->__('No change'),
            self::PRICE_TYPE_DYNAMIC_POS => $helper->__('Relative increase (+ %)'),
            self::PRICE_TYPE_DYNAMIC_NEG => $helper->__('Relative decrease (- %)'),
            self::PRICE_TYPE_FIXED_POS => $helper->__('Absolute increase (+)'),
            self::PRICE_TYPE_FIXED_NEG => $helper->__('Absolute decrease (-)')
        );
    }

}