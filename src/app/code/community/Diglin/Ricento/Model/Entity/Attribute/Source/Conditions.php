<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 *
 * @deprecated see Diglin_Ricento_Model_Config_Source_Sales_Product_Condition instead (no EAV model)
 */
class Diglin_Ricento_Model_Entity_Attribute_Source_Conditions extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        // TODO: Implement getArticleConditions() method from Ricardo API. Here it's just temporary returned array
        if (is_null($this->_options)) {
            $helper = Mage::helper('diglin_ricento');

            $this->_options = array(
                array(
                    'label' => $helper->__('Neu und originalverpackt'),
                    'value' => 'new'
                ),
                array(
                    'label' => $helper->__('Neu (gemäss Beschreibung)'),
                    'value' => 'refurbished'
                ),
                array(
                    'label' => $helper->__('Gebraucht'),
                    'value' => 'second_hand'
                ),
                array(
                    'label' => $helper->__('Antik'),
                    'value' => 'antic'
                ),
                array(
                    'label' => $helper->__('Defekt'),
                    'value' => 'defect'
                ),
            );
        }
        return $this->_options;
    }

}