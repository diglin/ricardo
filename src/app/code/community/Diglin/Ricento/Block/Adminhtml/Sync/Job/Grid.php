<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Sync_Job_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('SyncJobGrid');
        $this->setDefaultSort('job_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /* @var $collection Diglin_Ricento_Model_Resource_Sync_Job_Collection */
        $collection = Mage::getResourceModel('diglin_ricento/sync_job_collection');
        $collection
            ->join('diglin_ricento/products_listing',
                'products_listing_id=entity_id',
                'title'
            )
            ->addFieldToFilter('job_type', array('in' => Diglin_Ricento_Model_Sync_Job::TYPE_SYNC));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('job_id', array(
            'header' => $this->__('ID') ,
            'align' => 'left',
            'index' => 'job_id',
            'type' => 'number',
            'width' => 50
        ));

        $this->addColumn('title', array(
            'header' => $this->__('Products Listing Name') ,
            'align' => 'left',
            'index' => 'title',
            'type' => 'text',
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );
        $this->addColumn('started_at', array(
            'header' => $this->__('Started at') ,
            'align'  => 'left',
            'index'  => 'started_at',
            'type'   => 'date',
            'width'  => 50,
            'format' => $dateFormatIso
        ));

        $this->addColumn('check', array(
            'header'    => $this->__('Product Data Control') ,
            'index'     => 'check',
            'sortable'  => false,
            'filter'    => false,
            'width'     => 150,
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_sync_job_grid_renderer_check')
        ));

        $this->addColumn('status', array(
            'header'    => $this->__('Status') ,
            'align'     => 'left',
            'index'     => 'status',
            'type'      => 'options',
            'width'     => 100,
            'options'   => Mage::getSingleton('diglin_ricento/config_source_sync_status')->toOptionHash()
        ));

        $this->addColumn('action',
            array(
                'header'    => $this->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => $this->__('Synchronize'),
                        'popup'   => true,
                        'url'     => array(
                            'base'=>'*/sync/list',
                            //'params' => array('id' => $this->getListing()->getId())
                        ),
                        'field'   => 'product'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
            ));


        return parent::_prepareColumns();
    }
}