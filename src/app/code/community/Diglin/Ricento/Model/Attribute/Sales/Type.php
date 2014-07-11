<?php
class Diglin_Ricento_Model_Attribute_Sales_Type implements Mage_Eav_Model_Entity_Attribute_Source_Interface
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
            ''         => '- Please Select -',
            'auction'  => 'Auction',
            'fixprice' => 'Fix price'
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