<?php
class Diglin_Ricento_Model_Config_Source_Rules_Shipping extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            '' => '- Please Select -',
            1  => 'Take away',
            2  => 'Mail A Post',
            3  => 'Mail B Post',
            4  => 'Package A Post',
            5  => 'Package B Post',
            6  => 'DHL',
            7  => 'DPS',
            8  => 'UPS',
            9  => 'TNT',
            10 => 'Flat',
            11 => 'Other (description)'
        );
    }
}