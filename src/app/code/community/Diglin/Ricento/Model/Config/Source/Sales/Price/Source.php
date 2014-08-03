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
 * Class Diglin_Ricento_Model_Config_Source_Sales_Price_Source
 */
class Diglin_Ricento_Model_Config_Source_Sales_Price_Source extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        $entityType = Mage::getModel('eav/entity_type');
        $collection = $entityType->loadByCode(Mage_Catalog_Model_Product::ENTITY)
            ->getAttributeCollection();

        $items = $collection
            ->addFieldToFilter('backend_type', Varien_Db_Ddl_Table::TYPE_DECIMAL)
            ->getItems();

        $options = array('' => Mage::helper('diglin_ricento')->__('-- Select Product Attribute --'));
        foreach ($items as $item) {
            $options[$item->getAttributeCode()] = $item->getFrontendLabel();
        }

        return $options;
    }
}