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
 * Class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition_Source
 */
class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition_Source extends Diglin_Ricento_Model_Config_Source_Abstract
{

    public function getAllOptions()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            array('label' => $helper->__('-- Please Select Attribute --'), 'value' => '', ),
            array('label' => $helper->__('Ricardo'),                     'value' => array(
                array('label' => $helper->__('Title'),       'value' => 'ricardo_title'),
                array('label' => $helper->__('Subtitle'),    'value' => 'ricardo_subtitle'),
                array('label' => $helper->__('Condition'),   'value' => 'ricardo_condition'),
                array('label' => $helper->__('Description'), 'value' => 'ricardo_description'),
            ))
        );
    }

    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        // not needed, since getAllOptions is implemented individually
        return array();
    }
}