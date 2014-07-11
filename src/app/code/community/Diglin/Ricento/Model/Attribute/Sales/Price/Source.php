<?php
class Diglin_Ricento_Model_Attribute_Sales_Price_Source implements Mage_Eav_Model_Entity_Attribute_Source_Interface
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
            ''              => '- Select Product Attribute -',
            'price'         => 'Price',
            'special_price' => 'Special Price',
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