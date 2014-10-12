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
        $this->setDefaultSort('entity_id');
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
        return $this->getUrl('*/products_listing_item/configure', array('id' => $this->getListing()->getId(), 'product' => $item->getId()));
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
                '{{table}}.products_listing_id='.(int) $this->getRequest()->getParam('id', 0),
                'left'
            )->joinField('sales_options_id',
                'diglin_ricento/products_listing_item',
                'sales_options_id',
                'product_id=entity_id',
                '{{table}}.products_listing_id='.(int) $this->getRequest()->getParam('id', 0),
                'left'
            )->joinField('rule_id',
                'diglin_ricento/products_listing_item',
                'rule_id',
                'product_id=entity_id',
                '{{table}}.products_listing_id='.(int) $this->getRequest()->getParam('id', 0),
                'left'
            )->joinField('has_custom_options',
                'catalog/product_option',
                new Zend_Db_Expr('option_id IS NOT NULL'),
                'product_id=entity_id',
                null,
                'left'
            )
        ->distinct(true);

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
            'header'    => Mage::helper('catalog')->__('Product ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id',
            'type'      => 'number'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name'
        ));

        $store = Mage::app()->getWebsite($this->getListing()->getWebsiteId())->getDefaultStore();
        $this->addColumn('price', array(
            'header'=> Mage::helper('catalog')->__('Price'),
            'type'  => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
            'sortable' => false,
            'filter'    => false,
        ));
        $this->addColumn('type', array(
            'header'    => Mage::helper('catalog')->__('Type'),
            'index'     => 'type_id',
            'width'     => '120',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));
        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => '120',
            'index'     => 'sku'
        ));
        $this->addColumn('qty', array(
            'header'    => Mage::helper('catalog')->__('Inventory'),
            'type'      => 'number',
            'width'     => '1',
            'index'     => 'stock_qty'
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('catalog')->__('Status'),
            'width'     => '80',
            'index'     => 'status',
            'type'     => 'options',
            'sortable' => true,
            'options'  => Mage::getSingleton('diglin_ricento/config_source_status')->toOptionHash(),
            'frame_callback' => array($this, 'decorateStatus')
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
                'getter'     => 'getId',
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
        $this->setMassactionIdField('entity_id');
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
            $products = $this->getListing()->getProductIds();
        }
        return $products;
    }

    /**
     * @param $value
     * @param Varien_Object $row
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return string
     */
    public function decorateStatus($value, Varien_Object $row, Mage_Adminhtml_Block_Widget_Grid_Column $column)
    {
        $output = '';
        $value = htmlspecialchars_decode($value);
        if ($column->getIndex() == 'status' && !empty($value)) {
            switch ($value) {
                case 'Error':
                    $output .= '<div id="message-errors"' . $row->getId() . ' class="message_errors">' . $value . '</div>';
                    break;
                case 'Listed':
                    $output .= '<div id="message-success"' . $row->getId() . ' class="message_success">' . $value . '</div>';
                    break;
                default:
                    $output .= '<div id="message-warnings"' . $row->getId() . ' class="message_warnings">' . $value . '</div>';
                    break;
            }
        }
        return $output;
    }

}
