<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Sales_Price_Source
 */
class Diglin_Ricento_Model_Config_Source_Sales_Price_Source extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @var array
     */
    protected $_options = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_options)) {
            $collection = Mage::getModel('eav/entity_type')
                ->loadByCode(Mage_Catalog_Model_Product::ENTITY)
                ->getAttributeCollection();

            $items = $collection
                ->addFieldToFilter('backend_type', Varien_Db_Ddl_Table::TYPE_DECIMAL)
                ->getItems();

            if (!empty($items)) {
                $this->_options = array('' => Mage::helper('diglin_ricento')->__('-- Select Product Attribute --'));
            }

            foreach ($items as $item) {
                if ($item->getAttributeCode() != 'price' && $item->getAttributeCode() != 'special_price') {
                    continue;
                }
                $this->_options[$item->getAttributeCode()] = $item->getFrontendLabel();
            }
        }

        return $this->_options;
    }
}