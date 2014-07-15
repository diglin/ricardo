<?php
class Diglin_Ricento_Model_Config_Source_Sales_Type extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            ''         => '- Please Select -',
            'auction'  => 'Auction',
            'fixprice' => 'Fix price'
        );
    }

}