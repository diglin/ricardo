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
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('itemLogGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare the collection to be displayed as a grid
     *
     * @return Varien_Data_Collection
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('diglin_ricento/products_listing_log_collection');
        $collection
            ->getSelect()
            ->join(array('pl' => $collection->getTable('diglin_ricento/products_listing')),
                'pl.entity_id = main_table.products_listing_id', 'title');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare the columns to generate the column
     *
     * @return Diglin_Ricento_Block_Adminhtml_Products_Listing_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('job_id', array(
            'header' => $this->__('Job ID') ,
            'align' => 'left',
            'index' => 'job_id',
            'type' => 'number',
            'width' => 50,
        ));

        if ($this->getNameInLayout() == 'ricento_listing_log') {
            $this->addColumn('products_listing_id', array(
                'header' => $this->__('Listing ID') ,
                'align' => 'left',
                'index' => 'products_listing_id',
                'type' => 'number',
                'width' => 50,
            ));

            $this->addColumn('title', array(
                'header' => $this->__('Listing Title'),
                'align' => 'left',
                'index' => 'title',
                'type' => 'text',
                'width' => 150,
                'frame_callback' => array($this, 'addListingUrl')
            ));
        }

        $this->addColumn('product_id', array(
            'header' => $this->__('Product ID') ,
            'align' => 'left',
            'index' => 'product_id',
            'type' => 'number',
            'width' => 50,
        ));

        $this->addColumn('product_title', array(
            'header' => $this->__('Product title') ,
            'align' => 'left',
            'index' => 'product_title',
            'frame_callback' => array($this, 'addConfigureUrl')
        ));

        $this->addColumn('message', array(
            'header' => $this->__('Message') ,
            'align' => 'left',
            'index' => 'message',
            'type' => 'text',
            'string_limit' => 10000, // very long
            'frame_callback' => array($this, 'prepareMessage')
        ));

        $this->addColumn('log_status', array(
            'header' => $this->__('Status') ,
            'align' => 'left',
            'index' => 'log_status',
            'type'  => 'options',
            'width' => 100,
            'options' => Mage::getSingleton('diglin_ricento/config_source_products_listing_status')->toOptionHash(),
            'frame_callback' => array($this, 'cellContainer')
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );
        $this->addColumn('created_at', array(
            'header' => $this->__('Created at') ,
            'align'  => 'left',
            'index'  => 'created_at',
            'type'   => 'date',
            'width'  => 200,
            'gmtoffset' => true,
            'format' => $dateFormatIso
        ));
    }

    /**
     * Prepare the mass action drop down menu
     *
     * @return Diglin_Ricento_Block_Adminhtml_Sync_Log_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('log_id');
        $this->getMassactionBlock()->setFormFieldName('listing_logs_grid');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> $this->__('Delete'),
            'url'  => $this->getUrl('*/*/massListingDelete', array('_current'=>true)),
            'confirm' => $this->__('Are you sure that you want to delete this/these log(s)?')
        ));

        return $this;
    }

    /**
     * @param $value
     * @param Varien_Object $row
     * @return string
     */
    public function addConfigureUrl($value, Varien_Object $row)
    {
        $url = $this->getUrl('*/products_listing_item/configure', array('id' => $row->getProductsListingId(), 'product' => $row->getProductId()));
        return '<a href="' . $url . '">' . $value . '</a>';
    }

    /**
     * @param $value
     * @param Varien_Object $row
     * @param $column
     * @return string
     */
    public function addListingUrl($value, Varien_Object $row, $column)
    {
        $url = $this->getUrl('*/products_listing/edit', array('id' => $row->getProductsListingId()));
        return '<a href="' . $url . '">' . $value . '</a>';
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
        if ($column->getIndex() == 'log_status' && !empty($value)) {
            $class = 'class="' . strtolower($column->getIndex()) . '-' . strtolower($value) . '"';
        }

        return '<div id="' . strtolower($column->getIndex()) . $row->getId() . '" ' . $class . '>' . $value . '</div>';
    }

    /**
     * @param $value
     * @param Varien_Object $row
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return string
     */
    public function prepareMessage($value, Varien_Object $row, Mage_Adminhtml_Block_Widget_Grid_Column $column)
    {
        $output = '';
        $value = htmlspecialchars_decode($value);
        if ($column->getIndex() == 'message' && !empty($value)) {
            $messages = Mage::helper('core')->jsonDecode($value);
            isset($messages['errors']) && count($messages['errors']) > 0 && $output .= '<div id="message-errors"' . $row->getId() . ' class="message_errors">' . implode('<br>', (array) $messages['errors']) . '</div>';
            isset($messages['warnings']) && count($messages['warnings']) > 0 && $output .= '<div id="message-warnings"' . $row->getId() . ' class="message_warnings">' . implode('<br>', (array) $messages['warnings']) . '</div>';
            isset($messages['notice']) && count($messages['notice']) > 0 && $output .= '<div id="message-notice"' . $row->getId() . ' class="message_notice">' . implode('<br>', (array) $messages['notice']) . '</div>';
            isset($messages['success']) && count($messages['success']) > 0 && $output .= '<div id="message-success"' . $row->getId() . ' class="message_success">' . implode('<br>', (array) $messages['success']) . '</div>';
        }
        return $output;
    }
}