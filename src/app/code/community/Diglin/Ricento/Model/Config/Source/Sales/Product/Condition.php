<?php
class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            '' => '- Please Select -',
            1  => 'New',
            2  => 'Refurbished',
            3  => 'Antic',
            4  => 'Secondhand',
            5  => 'Defect'
        );
    }

}