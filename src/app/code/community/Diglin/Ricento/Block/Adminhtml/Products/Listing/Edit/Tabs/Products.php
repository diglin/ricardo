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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products
    extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_massactionBlockName = 'diglin_ricento/adminhtml_products_listing_edit_tabs_products_massaction';

    public function __construct()
    {
        parent::__construct();
        $this->setId('products_listing_items');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
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
        return $this->getUrl('*/products_listing_item/configure', array('id' => $this->getListing()->getId(), 'product' => $item->getId(), 'item' => $item->getItemId()));
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

        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('type_id')
            ->addAttributeToSelect('price')
            ->addWebsiteFilter($this->getListing()->getWebsiteId())
            ->joinField('stock_qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left'
            )->joinField('status',
                'diglin_ricento/products_listing_item',
                'status',
                'product_id=entity_id',
                '{{table}}.products_listing_id='.(int) $this->getRequest()->getParam('id', 0) . ' AND at_status.parent_item_id IS NULL',
                'left'
            )->joinField('sales_options_id',
                'diglin_ricento/products_listing_item',
                'sales_options_id',
                'product_id=entity_id',
                '{{table}}.products_listing_id='.(int) $this->getRequest()->getParam('id', 0) . ' AND at_sales_options_id.parent_item_id IS NULL',
                'left'
            )->joinField('rule_id',
                'diglin_ricento/products_listing_item',
                'rule_id',
                'product_id=entity_id',
                '{{table}}.products_listing_id='.(int) $this->getRequest()->getParam('id', 0) . ' AND at_rule_id.parent_item_id IS NULL',
                'left'
            )->joinField('ricardo_article_id',
                'diglin_ricento/products_listing_item',
                'ricardo_article_id',
                'product_id=entity_id',
                '{{table}}.products_listing_id='.(int) $this->getRequest()->getParam('id', 0) . ' AND at_ricardo_article_id.parent_item_id IS NULL',
                'left'
            )->joinField('item_id',
                'diglin_ricento/products_listing_item',
                'item_id',
                'product_id=entity_id',
                '{{table}}.products_listing_id='.(int) $this->getRequest()->getParam('id', 0) . ' AND at_item_id.parent_item_id IS NULL',
                'left'
            )->distinct(true);

        $productIds = $this->_getSelectedProducts();
        if (empty($productIds)) {
            $productIds = 0;
        }
        $collection->addFieldToFilter('entity_id', array('in'=>$productIds));

        $this->getColumn('massaction')->setUseIndex(true);


        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('item_id', array(
            'header'    => Mage::helper('catalog')->__('Item ID'),
            'sortable'  => true,
            'filter'    => false,
            'width'     => '60',
            'index'     => 'item_id',
            'type'      => 'number'
        ));

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('catalog')->__('Product ID'),
            'sortable'  => true,
            'filter'    => false,
            'width'     => '60',
            'index'     => 'entity_id',
            'type'      => 'number'
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name',
            'width'     => '300',
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_edit_tabs_products_renderer_name')
        ));

        $store = Mage::app()->getWebsite($this->getListing()->getWebsiteId())->getDefaultStore();
        $this->addColumn('price', array(
            'header'        => Mage::helper('diglin_ricento')->__('Price Catalog'),
            'type'          => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index'         => 'price',
            'sortable'      => false,
            'filter'        => false,
        ));
        $this->addColumn('type', array(
            'header'    => Mage::helper('catalog')->__('Type'),
            'index'     => 'type_id',
            'width'     => '120',
            'type'      => 'options',
            'options'   => array_intersect_key(Mage::getSingleton('catalog/product_type')->getOptionArray(), Mage::helper('diglin_ricento')->getAllowedProductTypes()),
        ));
        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => '120',
            'index'     => 'sku'
        ));

        $this->addColumn('ricardo_article_id', array(
            'header'    => Mage::helper('diglin_ricento')->__('ricardo.ch Article ID'),
            'width'     => '120',
            'index'     => 'ricardo_article_id',
            'type'      => 'number',
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_edit_tabs_products_renderer_article')
        ));

        $this->addColumn('qty', array(
            'header'    => Mage::helper('catalog')->__('Inventory'),
            'type'      => 'number',
            'width'     => '1',
            'index'     => 'stock_qty',
            'sortable'  => false,
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
                'getter'     => 'getItemId',
                'actions'   => array(
                    array(
                        'caption' => $this->__('Preview'),
                        'field'   => 'item',
                        'popup'   => true,
                        'url'     => array(
                            'base'=>'*/products_listing_item/preview',
                            'params' => array('id' => $this->getListing()->getId())
                        )
                    ),
                    array(
                        'caption' => $this->__('Configure'),
                        'field'   => 'item',
                        'url'     => array(
                            'base'=>'*/products_listing_item/configure',
                            'params' => array('id' => $this->getListing()->getId())
                        )
                    ),
                    array(
                        'caption' => $this->__('Remove'),
                        'field'   => 'item',
                        'url'     => array(
                            'base'   => '*/*/removeProduct',
                            'params' => array('id' => $this->getListing()->getId()),
                        ),
                        'confirm' => $this->__('Are you sure to remove this/these product(s)?'),
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
            ));

        return parent::_prepareColumns();
    }

    /**
     * @return $this|Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('item');
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

    /**
     * @return array|mixed
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if (is_null($products)) {
            $products = $this->getListing()->getProductIds(false);
        }
        return $products;
    }
}
