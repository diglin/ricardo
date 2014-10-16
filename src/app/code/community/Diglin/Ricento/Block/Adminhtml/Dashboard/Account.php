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
        /** @var Diglin_Ricento_Model_Resource_Api_Token_Collection $collection */
        $collection = Mage::getResourceModel('diglin_ricento/api_token_collection');
        $collection
            ->join(array('website' => 'core/website'), 'website.website_id=main_table.website_id', array('website_name' => 'name'))
            ->addFieldToSelect(array('entity_id', 'token', 'website_id'))
            ->addFieldToFilter('token_type', 'identified');
        //TODO include language in collection

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('website_id', array(
            'header'    => $this->__('Website'),
            'index'     => 'website_name',
            'type'      => 'text'
        ));

        $this->addColumn('language', array(
            'header'    => $this->__('Lang'),
            'sortable'  => false,
            'width'     => '30px',
            'index'     => 'language',
            'type'      => 'text'
        ));

        $this->addColumn('api_token', array(
            'header'    => $this->__('Token'),
            'sortable'  => false,
            'index'     => 'token',
            'type'      => 'text'
        ));

        $this->addColumn('action', array(
            'header'    => $this->__('Action'),
            'sortable'  => false,
            'width'     => '100px',
            'renderer'  => 'diglin_ricento/adminhtml_widget_grid_column_renderer_button',
            'index' => 'entity_id',
            'actions' => array(
                array(
                    'caption' => $this->__('Unlink'),
                    'url'     => array('base' => '*/api/unlinkToken'),
                    'confirm' => $this->__('Are you sure?'),
                    'field'   => 'entity_id',
                    'class'   => 'delete'
                )),
        ));
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);

        return parent::_prepareColumns();
    }
}
