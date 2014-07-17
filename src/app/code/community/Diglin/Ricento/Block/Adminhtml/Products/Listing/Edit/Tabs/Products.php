<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products
    extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('products_listing_items');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsGrid', array('id' => $this->getListing()->getId()));
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
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('type_id')
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
                'products_listing_id='.(int) $this->getRequest()->getParam('id', 0),
                'left');

        $productIds = $this->_getSelectedProducts();
        if (empty($productIds)) {
            $productIds = 0;
        }
        $collection->addFieldToFilter('entity_id', array('in'=>$productIds));

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
            'options'   => Mage::getSingleton('catalog/product_type')->getOptionArray(),
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
        $this->addColumn('action',
            array(
                'header'    => $this->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => $this->__('Configure'),
                        'url'     => array(
                            'base'=>'*/products_listing_item/configure', //FIXME use configurePopup action if popup works
                        ),
                        'onclick' => 'Ricento.configureItemPopup(this.href); return false;', //FIXME "onclick" funktioniert nicht mit Magento Action Column Renderer und grid.js, "href=javascript:..." auch nicht, da href dynamisch erstellt wird
                        'field'   => 'id'
                    ),
                    array(
                        'caption' => $this->__('Remove'),
                        'url'     => array(
                            'base'   => '*/*/removeProduct',
                            'params' => array('id' => $this->getListing()->getId()),
                        ),
                        'field'   => 'product',
                        'confirm' => $this->__('Are you sure?'),
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
            ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');
        $this->getMassactionBlock()->setHideFormElement(true);

        $this->getMassactionBlock()->addItem('remove', array(
            'label'=> $this->__('Remove from list'),
            'url'  => $this->getUrl('*/products_listing/removeProduct', array('id' => $this->getListing()->getId())),
            'confirm' => $this->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('configure', array(
            'label' => $this->__('Configure'),
            'url'   => // "javascript:Ricento.configureItemPopup('" .
                       $this->getUrl('*/products_listing_item/configure', array('id' => $this->getListing()->getId(), '_current' => true))
                       //. "');"
                       //FIXME URL in JavaScript bekommt die ausgewählten items nicht als Parameter angehängt
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
