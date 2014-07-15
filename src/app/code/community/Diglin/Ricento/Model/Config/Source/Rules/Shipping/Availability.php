<?php
class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Availability extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            '' => '- Select Availability -',
            1  => '1 business day',
            2  => '2 business days',
            3  => '3 business days',
            4  => 'None (description)',
        );
    }
}