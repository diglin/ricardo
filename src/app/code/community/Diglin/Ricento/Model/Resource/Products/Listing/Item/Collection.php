<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Collection of Products_Listing_Item
 */
class Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * @var bool
     */
    protected $_isProductTableJoined = false;

    /**
     * @var bool
     */
    protected $_isProductConfigurableTableJoined = false;

    /**
     * @var bool
     */
    protected $_addAdditionalInformation = false;

    /**
     * Products_Listing_Item Collection Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/products_listing_item');
    }

    /**
     * @param $status
     * @return $this
     */
    public function updateStatusToAll($status)
    {
        $connection = $this->getConnection();
        $connection->update(
            $this->getTable('diglin_ricento/products_listing_item'),
                array('status' => $status),
                array('item_id IN (?)' => $this->getAllIds())
        );

        return $this;
    }

    /**
     * Get only items collection being a configurable product
     *
     * @return $this
     */
    public function getConfigurableProducts()
    {
        if (!$this->_isProductConfigurableTableJoined) {
            $this
                ->getSelect()
                ->join(
                    array('pl' => $this->getTable('catalog/product')),
                    "pl.entity_id = main_table.product_id",
                    array('product_type' => 'type_id')
                )
                ->where('type_id = ?', Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE);

            $this->_isProductConfigurableTableJoined = true;
        }
        return $this;
    }

    /**
     * Get only items collection being a configurable product
     *
     * @return $this
     */
    public function getProductsWithoutConfigurable()
    {
        if (!$this->_isProductTableJoined) {
            $this
                ->getSelect()
                ->join(
                    array('pl' => $this->getTable('catalog/product')),
                    "pl.entity_id = main_table.product_id",
                    array('product_type' => 'type_id')
                )
                ->where('type_id <> ?', Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE);

            $this->_isProductTableJoined = true;
        }
        return $this;
    }

    public function getAddAdditionalInformation()
    {
        return $this->_addAdditionalInformation;
    }

    public function setAddAdditionalInformation($var)
    {
        $this->_addAdditionalInformation = $var;
        return $this;
    }

    /**
     * Helper to filter some fields from extra/joined tables
     *
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     */
    public function addGridFilter(Mage_Adminhtml_Block_Widget_Grid_Column $column)
    {
        $valueToSearch = $column->getFilter()->getValue();
        $config = Mage::getSingleton('eav/config');

        switch($column->getId()){
            case 'product_name':
                $productName = $config->getAttribute('catalog_product','name');
                $this->getSelect()->join(array('product_attributes' => $productName->getBackendTable()),
                    'main_table.product_id = product_attributes.entity_id',
                    array());

                $condition = "product_attributes.attribute_id  = '".$productName->getId() . "'";
                $this->addFilter('product_attributes.attribute_id', $condition , 'string');//1st param useless here
                $this->addFieldToFilter('product_attributes.value', array('like' => '%'.$valueToSearch.'%'));
                break;

            case 'product_sku':
                $this->getSelect()->join(
                    array('product_entity' => $this->getTable('catalog/product/entity')),
                    'main_table.product_id = product_entity.entity_id',
                    array());

                $this->addFieldToFilter('product_entity.sku',array('like' => '%'.$valueToSearch.'%'));
                break;
        }
    }
}