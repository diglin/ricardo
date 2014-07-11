<?php
class Diglin_Ricento_Model_Attribute_Shipping_Availability implements Mage_Eav_Model_Entity_Attribute_Source_Interface
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
        '' => '- Select Availability -',
        1  => '1 business day',
        2  => '2 business days',
        3  => '3 business days',
        4  => 'None (description)',
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