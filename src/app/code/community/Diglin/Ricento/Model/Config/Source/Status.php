<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Status
 */
class Diglin_Ricento_Model_Config_Source_Status implements Mage_Eav_Model_Entity_Attribute_Source_Interface
{
    /**
     * Create option array to display in a form the status of a product listing
     *
     * @return array
     */
    public function getAllOptions()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(

            array(
                'value' => '' ,
                'label' => $helper->__('-- Please Select --')
            ), // Products are not yet listed into ricardo
            array(
                'value' => Diglin_Ricento_Helper_Data::STATUS_PENDING,
                'label' => $helper->__('Pending')
            ),  // Products are listed into ricardo and are in progress of sales
            array(
                'value' => Diglin_Ricento_Helper_Data::STATUS_LISTED,
                'label' => $helper->__('List')
            ),  // Products are listed into ricardo and are in progress of sales
            array(
                'value' => Diglin_Ricento_Helper_Data::STATUS_STOPPED,
                'label' => $helper->__('Stop')
            ),  // Products are not listed or having been stopped from ricardo
            array(
                'value' => Diglin_Ricento_Helper_Data::STATUS_ERROR,
                'label' => $helper->__('Error')
            )
        );
    }

    /**
     * This method is used for the massaction for example
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * This method is used for grid filter
     */
    public function toOptionHash()
    {
        $hash = array();
        foreach ($this->getAllOptions() as $option) {
            $hash[$option['value']] = $option['label'];
        }
        unset($hash['']);
        return $hash;
    }

    /**
     * @param string $value
     * @return mixed|void
     */
    public function getOptionText($value)
    {
        $map = $this->toOptionHash();
        return isset($map[$value]) ? $map[$value] : null;
    }
}