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
 * Class Diglin_Ricento_Model_Config_Source_Shipping_Calculation
 */
class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Calculation
{
    const HIGHEST_PRICE = 'highest_price';

    const CUMULATIVE = 'cumulative';

    /**
     * Create option array to display the list of possible options for shipping calculation
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(

            array(
                'value' => self::HIGHEST_PRICE,
                'label' => $helper->__('Highest Price')
            ),
            array(
                'value' => self::CUMULATIVE,
                'label' => $helper->__('Cumulative')
            )
        );
    }
}