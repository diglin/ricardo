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
 * Class Diglin_Ricento_Model_Config_Source_Shipping_Calculation
 */
class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Calculation
{
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
                'value' => 'highest_price' ,
                'label' => $helper->__('Highest Price')
            ),
            array(
                'value' => 'cumulative' ,
                'label' => $helper->__('Cumulative')
            )
        );
    }
}