<?php
/*
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Dashboard_Bestsellers
 */
class Diglin_Ricento_Block_Adminhtml_Dashboard_Bestsellers extends Mage_Adminhtml_Block_Dashboard_Grid
{
    protected $_collection;

    public function __construct()
    {
        parent::__construct();
        $this->setId('bestsellerGrid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('diglin_ricento/sales_transaction_collection');
        //TODO aggregate data

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header'    => $this->__('Product Name'),
            'type'      => 'text'
        ));

        $this->addColumn('sku', array(
            'header'    => $this->__('SKU'),
            'sortable'  => false,
            'type'      => 'text'
        ));

        $this->addColumn('price', array(
            'header'    => $this->__('Price'),
            'sortable'  => false,
            'type'      => 'price'
        ));

        $this->addColumn('qty', array(
            'header'    => $this->__('Qty sold'),
            'sortable'  => false,
            'index'     => 'qty',
            'type'      => 'number'
        ));
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);

        return parent::_prepareColumns();
    }
}