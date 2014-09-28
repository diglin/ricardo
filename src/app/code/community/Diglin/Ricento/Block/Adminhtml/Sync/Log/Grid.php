<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Diglin_Ricento_Block_Adminhtml_Sync_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('SyncJobGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /* @var $collection Diglin_Ricento_Model_Resource_Sync_Job_Collection */
        $collection = Mage::getResourceModel('diglin_ricento/sync_job_collection');

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
            'filter'    => false,
            'width' => 50
        ));

        $this->addColumn('job_type', array(
            'header' => $this->__('Job Type') ,
            'align' => 'left',
            'index' => 'job_type',
            'type' => 'options',
            'width' => 150,
            'options'   => Mage::getSingleton('diglin_ricento/config_source_sync_type')->toOptionHash()
        ));

        $this->addColumn('job_message', array(
            'header' => $this->__('Job Message') ,
            'align' => 'left',
            'index' => 'job_message',
            'type' => 'text',
            'string_limit' => 10000, // very long
            'frame_callback' => array($this, 'cellContainer')
        ));

        $this->addColumn('current_progress', array(
            'header'    => $this->__('Current Progress') ,
            'index'     => 'current_progress',
            'sortable'  => false,
            'filter'    => false,
            'width'     => 150,
            'renderer'  => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_sync_log_grid_renderer_progress')
        ));

        $this->addColumn('job_status', array(
            'header'    => $this->__('Status') ,
            'align'     => 'left',
            'index'     => 'job_status',
            'type'      => 'options',
            'width'     => 100,
            'frame_callback' => array($this, 'cellContainer'),
            'options'   => Mage::getSingleton('diglin_ricento/config_source_sync_status')->toOptionHash()
        ));

        $dateFormatIso = Mage::helper('diglin_ricento')->getDateTimeIsoFormat();

        $this->addColumn('started_at', array(
            'header' => $this->__('Started at') ,
            'align'  => 'left',
            'index'  => 'started_at',
            'type'   => 'date',
            'width'  => 150,
            'format' => $dateFormatIso,
            'gmtoffset' => true,
            'frame_callback' => array($this, 'cellContainer'),
        ));

        $this->addColumn('ended_at', array(
            'header' => $this->__('Ended at') ,
            'align'  => 'left',
            'index'  => 'ended_at',
            'type'   => 'date',
            'width'  => 150,
            'frame_callback' => array($this, 'cellContainer'),
            'format' => $dateFormatIso,
            'gmtoffset' => true
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );

        $this->addColumn('created_at', array(
            'header' => $this->__('Created at') ,
            'align'  => 'right',
            'index'  => 'created_at',
            'type'   => 'date',
            'width'  => 150,
            'format' => $dateFormatIso,
            'gmtoffset' => true
        ));

        return parent::_prepareColumns();
    }

    /**
     * Prepare the mass action drop down menu
     *
     * @return Diglin_Ricento_Block_Adminhtml_Sync_Log_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('job_id');
        $this->getMassactionBlock()->setFormFieldName('jobs_grid');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> $this->__('Delete'),
            'url'  => $this->getUrl('*/*/massSyncDelete', array('_current'=>true)),
            'confirm' => $this->__('Are you sure that you want to delete this/these job(s)?')
        ));

        return $this;
    }

    /**
     * @param $value
     * @param Varien_Object $row
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return string
     */
    public function cellContainer($value, Varien_Object $row, Mage_Adminhtml_Block_Widget_Grid_Column $column)
    {
        $class = '';
        if ($column->getIndex() == 'job_status' && !empty($row->getJobStatus())) {
            $class = 'class="' . strtolower($column->getIndex()) . '-' . strtolower($row->getJobStatus()) . '"';
        }

        if ($column->getIndex() == 'job_message') {
            $value = Mage::getSingleton('diglin_ricento/filter')->filter($value);
        }

        return '<div id="' . strtolower($column->getIndex()) . $row->getId() . '" ' . $class . '>' . $value . '</div>';
    }
}