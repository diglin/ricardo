<?php
class Diglin_Ricento_Model_Attribute_Sales_Promotion implements Mage_Eav_Model_Entity_Attribute_Source_Interface
{
    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = array();
        foreach ($this->getOptionHash() as $value => $label) {
            $options[] = array('label' => $label, 'value' => $value);
        }
        return $options;
    }

    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function getOptionHash()
    {
        //TODO implement
        return array(
            '' => 'None',
            1  => 'Small CHF 2.00',
            2  => 'Medium CHF 5.00',
            3  => 'Big CHF 12.00'
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
        $options = $this->getOptionHash();
        if (isset($options[$value])) {
            return $options[$value];
        }
        return null;
    }

}