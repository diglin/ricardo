<?php
class Diglin_Ricento_Model_Attribute_Payment implements Mage_Eav_Model_Entity_Attribute_Source_Interface
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
            0 => 'Bank Transfer',
            1 => 'Cash',
            2 => 'Credit Card (Payu - Ricardo service)',
            3 => 'Other (fill the description)'
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