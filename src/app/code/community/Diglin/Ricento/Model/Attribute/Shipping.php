<?php
class Diglin_Ricento_Model_Attribute_Shipping implements Mage_Eav_Model_Entity_Attribute_Source_Interface
{
    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        // TODO: Implement getAllOptions() method.
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

    /**
     * Retrieve Option value text
     *
     * @param string $value
     * @return mixed
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions();
        if (isset($options[$value])) {
            return $options[$value];
        }
        return null;
    }
}