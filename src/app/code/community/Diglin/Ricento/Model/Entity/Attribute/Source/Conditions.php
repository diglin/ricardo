<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
        // need to check if configured because it blocks users to edit product page until they receive API info
        if (Mage::helper('diglin_ricento')->isConfigured()) {
            if (is_null($this->_options)) {

                $this->_options = Mage::getSingleton('diglin_ricento/config_source_sales_product_condition')->getAllOptions();
            }
            return $this->_options;
        }

        return array('label' => Mage::helper('diglin_ricento')->__('No Options because API not configured'), 'value' => '');
    }
}