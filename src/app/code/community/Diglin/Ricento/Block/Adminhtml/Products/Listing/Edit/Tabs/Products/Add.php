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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Add
 *
 * "Add products" grid
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Add
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * @var Diglin_Ricento_Helper_Data
     */
    protected $_helper;

    public function __construct()
    {
        parent::__construct();
        $this->_helper = Mage::helper('diglin_ricento');
        $this->setId('products_listing_add');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/addProductsGrid', array('id' => $this->getListing()->getId()));
    }
    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    public function getListing()
    {
        return Mage::registry('products_listing');
    }

    protected function _prepareCollection()
    {
        if ($this->getListing()->getId()) {
            $this->setDefaultFilter(array('in_category'=>1));
        }
        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('type_id')
            ->addStoreFilter($this->getListing()->getStoreId())
            ->addAttributeToFilter('type_id', array('in' => $this->_helper->getAllowedProductTypes()))
            ->joinField('stock_qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left'
            )->joinField('in_other_list',
                'diglin_ricento/products_listing_item',
                new Zend_Db_Expr('products_listing_id IS NOT NULL'),
                'product_id=entity_id',
                'products_listing_id !='.(int) $this->getRequest()->getParam('id', 0),
                'left')
            ->groupByAttribute('entity_id');
        $productIds = $this->_getSelectedProducts();
        if (empty($productIds)) {
            $productIds = 0;
        }
        $collection->addFieldToFilter('entity_id', array('nin'=>$productIds));

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id',
            'type'      => 'number'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('type', array(
            'header'    => Mage::helper('catalog')->__('Type'),
            'index'     => 'type_id',
            'type'      => 'options',
            'options'   => array_intersect_key(Mage::getSingleton('catalog/product_type')->getOptionArray(), $this->_helper->getAllowedProductTypes()),
        ));
        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku'
        ));
        $this->addColumn('qty', array(
            'header'    => Mage::helper('catalog')->__('Inventory'),
            'type'      => 'number',
            'width'     => '1',
            'index'     => 'stock_qty'
        ));
        $this->addColumn('in_other_list', array(
            'header'    => $this->__('In other list?'),
            'type'      => 'options',
            'options'   => Mage::getModel('eav/entity_attribute_source_boolean')->getOptionArray(),
            'index'     => 'in_other_list',
            'sortable'  => false
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $this->getMassactionBlock()->addItem('add', array(
            'label'=> $this->__('Add selected product(s)'),
            'url'  => $this->getUrl('*/products_listing/addProduct', array('id' => $this->getListing()->getId()))
        ));

        return $this;
    }

    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if (is_null($products)) {
            $products = $this->getListing()->getProductIds();
        }
        return $products;
    }

}
