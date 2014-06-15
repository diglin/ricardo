<?php
/**
 * Diglin GmbH - Switzerland
 * 
 * User: sylvainraye
 * Date: 16.05.14
 * Time: 01:18
 *
 * @category    Diglin Magento Demo
 * @package     Diglin_
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */ 
class Diglin_Ricento_Model_Config_Source_Status
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
            ) ,
            array(
                'value' => Diglin_Ricento_Helper_Data::STATUS_LISTED ,
                'label' => $helper->__('List')
            ) ,  // Products are listed into ricardo and are in progress of sales
            array(
                'value' => Diglin_Ricento_Helper_Data::STATUS_STOPPED ,
                'label' => $helper->__('Stop')
            ) ,  // Products are not listed or having been stopped from ricardo
            array(
                'value' => Diglin_Ricento_Helper_Data::STATUS_ERROR ,
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
}