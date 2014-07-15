<?php
class Diglin_Ricento_Model_Config_Source_Rules_Payment extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            0 => 'Bank Transfer',
            1 => 'Cash',
            2 => 'Credit Card (Payu - Ricardo service)',
            3 => 'Other (fill the description)'
        );
    }

}