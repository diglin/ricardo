<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products
    extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('products_listing_items');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->setUseAjax(true);
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsGrid', array('id' => $this->getListing()->getId()));
    }

    /**
     * @param $item
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/products_listing_item/configure', array('id' => $this->getListing()->getId(), 'product' => $item->getProductId()));
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
            $this->setDefaultFilter(array('in_category' => 1));
        }

        $collection = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
        $collection->setAddAdditionalInformation(true);

        $productIds = $this->_getSelectedProducts();
        if (empty($productIds)) {
            $productIds = 0;
        }
        $collection->addFieldToFilter('main_table.product_id', array('in' => $productIds));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('item_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'filter'    => false,
            'width'     => '60',
            'index'     => 'item_id',
            'type'      => 'number'
        ));

        $this->addColumn('product_id', array(
            'header'    => Mage::helper('catalog')->__('Product ID'),
            'sortable'  => true,
            'filter'    => false,
            'width'     => '60',
            'index'     => 'product_id',
            'type'      => 'number'
        ));

        $this->addColumn('product_name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'product_name',
            'width'     => '300',
            'filter_condition_callback' => array($this, '_filterGridCondition'),
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_edit_tabs_products_renderer_name')
        ));

        $store = Mage::app()->getWebsite($this->getListing()->getWebsiteId())->getDefaultStore();
        $this->addColumn('price', array(
            'header'=> Mage::helper('diglin_ricento')->__('Price Catalog'),
            'type'  => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
            'sortable' => false,
            'filter'    => false,
        ));
        $this->addColumn('type_id', array(
            'header'    => Mage::helper('catalog')->__('Type'),
            'index'     => 'type_id',
            'width'     => '120',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));
        $this->addColumn('product_sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => '120',
            'index'     => 'product_sku',
        	'filter_condition_callback' => array($this, '_filterGridCondition'),
        ));

        $this->addColumn('ricardo_article_id', array(
            'header'    => Mage::helper('diglin_ricento')->__('ricardo.ch Article ID'),
            'width'     => '120',
            'index'     => 'ricardo_article_id',
            'type'      => 'text',
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_edit_tabs_products_renderer_article')
        ));

        $this->addColumn('qty_inventory', array(
            'header'    => Mage::helper('catalog')->__('Inventory'),
            'type'      => 'number',
            'width'     => '1',
            'index'     => 'qty_inventory',
            'sortable' => false,
            'filter'    => false,
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_edit_tabs_products_renderer_inventory')
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('catalog')->__('Status'),
            'width'     => '80',
            'index'     => 'status',
            'type'     => 'options',
            'sortable' => true,
            'options'  => Mage::getSingleton('diglin_ricento/config_source_status')->toOptionHash(),
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_edit_tabs_products_renderer_status')
        ));
        $this->addColumn('has_custom_options', array(
            'header'    => '',
            'width'     => 20,
            'index'     => 'has_custom_options',
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_edit_tabs_products_renderer_customoptions')
        ));
        $this->addColumn('is_configured', array(
            'header'    => '',
            'width'     => 20,
            'index'     => 'is_configured',
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_edit_tabs_products_renderer_configured')
        ));
        $this->addColumn('action',
            array(
                'header'    => $this->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'     => 'getProductId',
                'actions'   => array(
                    array(
                        'caption' => $this->__('Preview'),
                        'popup'   => true,
                        'url'     => array(
                            'base'=>'*/products_listing_item/preview',
                            'params' => array('id' => $this->getListing()->getId())
                        ),
                        'field'   => 'product'
                    ),
                    array(
                        'caption' => $this->__('Configure'),
                        'url'     => array(
                            'base'=>'*/products_listing_item/configure',
                            'params' => array('id' => $this->getListing()->getId())
                        ),
                        'field'   => 'product'
                    ),
                    array(
                        'caption' => $this->__('Remove'),
                        'url'     => array(
                            'base'   => '*/*/removeProduct',
                            'params' => array('id' => $this->getListing()->getId()),
                        ),
                        'field'   => 'product',
                        'confirm' => $this->__('Are you sure to remove this/these product(s)?'),
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
            ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('product_id');
        $this->getMassactionBlock()->setFormFieldName('product');
        $this->getMassactionBlock()->setHideFormElement(true);

        $this->getMassactionBlock()->addItem('remove', array(
            'label'=> $this->__('Remove from list'),
            'url'  => $this->getUrl('*/products_listing/removeProduct', array('id' => $this->getListing()->getId())),
            'confirm' => $this->__('Are you sure to remove this/these product(s)?')
        ));

        $this->getMassactionBlock()->addItem('configure', array(
            'label' => $this->__('Configure'),
            'url'   => $this->getUrl('*/products_listing_item/configure', array('id' => $this->getListing()->getId(), '_current' => true))
        ));

        return $this;
    }

    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if (is_null($products)) {
            $products = $this->getListing()->getProductIds(false);
        }
        return $products;
    }

    /**
     * Retreive data from joined database table
     *
     * @param $collection
     * @param string $column
     */
    protected function _filterGridCondition ($collection, $column)
    {
        if (! $value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addGridFilter($column);
    }
}
