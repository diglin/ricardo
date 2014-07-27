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
        return array(
            array('label' => '- Please Select Attribute -', 'value' => '', ),
//            array('label' => 'Price',                       'value' => 'price'),
//            array('label' => 'Special Price',               'value' => 'special_price'),
            array('label' => 'Ricardo',                     'value' => array(
                array('label' => 'Condition',   'value' => 'ricardo_condition'),
                array('label' => 'Description', 'value' => 'ricardo_description'),
                array('label' => 'Subtitle',    'value' => 'ricardo_subtitle'),
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