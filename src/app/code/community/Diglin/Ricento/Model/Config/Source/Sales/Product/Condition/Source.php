<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition_Source
 */
class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition_Source extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            'ricardo_condition' => $helper->__('ricardo.ch Condition')
        );
    }
}