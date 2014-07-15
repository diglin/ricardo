<?php
class Diglin_Ricento_Model_Config_Source_Sales_Price_Method extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            '' => '- Select Method -',
            1  => 'No change',
            2  => 'Relative increase',
            3  => 'Relative decrease',
            4  => 'Absolute increase',
            5  => 'Absolute decrease'
        );
    }

}