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
 * Class Diglin_Ricento_Block_Adminhtml_Dashboard_Account
 */
class Diglin_Ricento_Block_Adminhtml_Dashboard_Account extends Mage_Adminhtml_Block_Dashboard_Grid
{
    protected $_collection;

    public function __construct()
    {
        parent::__construct();
        $this->setId('accountGrid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('diglin_ricento/api_token_collection')
            ->addFieldToSelect(array('token','website_id'))
            ->addFieldToFilter('token_type', 'identify');
        //TODO join language

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('website_id', array(
            'header'    => $this->__('Store View'), //TODO website vs. store klären
            'index'     => 'website_id',
            'type'      => 'text'
            //'renderer'  => 'adminhtml/dashboard_searches_renderer_searchquery',
        ));

        $this->addColumn('language', array(
            'header'    => $this->__('Lang'),
            'sortable'  => false,
//            'index'     => 'language',
            'type'      => 'text'
        ));

        $this->addColumn('api_token', array(
            'header'    => $this->__('Token'),
            'sortable'  => false,
            'index'     => 'api_token',
            'type'      => 'text'
        ));

        $this->addColumn('action', array(
            'header'    => $this->__('Action'),
            'sortable'  => false,
            'type'      => 'action'
        ));
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);

        return parent::_prepareColumns();
    }
}
