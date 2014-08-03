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
        if (is_null($this->_options)) {

            $this->_options = Mage::getSingleton('diglin_ricento/config_source_sales_product_condition')->getAllOptions();
        }
        return $this->_options;
    }

}