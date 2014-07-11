<?php
class Diglin_Ricento_Model_Attribute_Sales_Price_Method implements Mage_Eav_Model_Entity_Attribute_Source_Interface
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
            '' => '- Select Method -',
            1  => 'No change',
            2  => 'Relative increase',
            3  => 'Relative decrease',
            4  => 'Absolute increase',
            5  => 'Absolute decrease'
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