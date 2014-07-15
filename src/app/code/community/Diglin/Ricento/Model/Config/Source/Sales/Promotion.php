<?php
class Diglin_Ricento_Model_Config_Source_Sales_Promotion extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        //TODO implement
        return array(
            '' => 'None',
            1  => 'Small CHF 2.00',
            2  => 'Medium CHF 5.00',
            3  => 'Big CHF 12.00'
        );
    }

}