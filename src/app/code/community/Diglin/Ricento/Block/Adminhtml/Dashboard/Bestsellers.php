<?php
/*
 * ricardo.ch AG - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Dashboard_Bestsellers
 */
class Diglin_Ricento_Block_Adminhtml_Dashboard_Bestsellers extends Mage_Adminhtml_Block_Dashboard_Grid
{
    const LIMIT = 20;

    protected $_collection;

    public function __construct()
    {
        parent::__construct();
        $this->setId('bestsellerGrid');
    }

    protected function _prepareCollection()
    {
        /** @var Diglin_Ricento_Model_Resource_Sales_Transaction_Collection $collection */
        $collection = Mage::getResourceModel('diglin_ricento/sales_transaction_collection');
        $collection->joinProducts()->setGroupByProduct(true);
        $collection->getSelect()
            ->columns('SUM(qty) AS qty')
            ->group('product_id')
            ->limit(self::LIMIT);
        $collection->setOrder('qty', Varien_Data_Collection::SORT_ORDER_DESC);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper('diglin_ricento');

        $this->addColumn('name', array(
            'header'    => $helper->__('Product Name'),
            'type'      => 'text',
            'index'     => 'product_name'
        ));

        $this->addColumn('sku', array(
            'header'    => $helper->__('SKU'),
            'sortable'  => false,
            'type'      => 'text',
            'index'     => 'product_sku'
        ));

        $this->addColumn('price', array(
            'header'    => $helper->__('Price'),
            'sortable'  => false,
            'type'      => 'currency',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'product_price'
        ));

        $this->addColumn('qty', array(
            'header'    => $helper->__('Qty sold'),
            'sortable'  => false,
            'index'     => 'qty',
            'type'      => 'number'
        ));
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);

        return parent::_prepareColumns();
    }
}