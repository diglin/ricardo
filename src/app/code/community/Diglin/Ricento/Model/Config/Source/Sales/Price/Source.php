<?php
class Diglin_Ricento_Model_Config_Source_Sales_Price_Source extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement, use attribute id as key or save attribute name in db
        return array(
            ''              => '- Select Product Attribute -',
            'price'         => 'Price',
            'special_price' => 'Special Price',
        );
    }

}