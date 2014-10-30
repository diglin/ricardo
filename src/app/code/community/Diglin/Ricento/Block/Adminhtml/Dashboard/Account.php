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
            ->addFieldToSelect(array('entity_id', 'token', 'website_id', 'expiration_date'))
            ->addFieldToFilter('token_type', 'identified');

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

        $this->addColumn('api_token', array(
            'header'    => $this->__('Token'),
            'sortable'  => false,
            'index'     => 'token',
            'type'      => 'text',
            'frame_callback' => array($this, 'secrecyToken')
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );
        $this->addColumn('expiration_date', array(
            'header'    => $this->__('Expiration'),
            'sortable'  => false,
            'index'     => 'expiration_date',
            'type'   => 'date',
            'width'  => 100,
            'gmtoffset' => true,
            'format' => $dateFormatIso
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

    /**
     * @param $value
     * @param Varien_Object $row
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return string
     */
    public function secrecyToken($value, Varien_Object $row, Mage_Adminhtml_Block_Widget_Grid_Column $column)
    {
        if ($column->getIndex() == 'token' && !empty($value)) {
            $value = explode('-', $value);
            $value[1] = 'XXXX';
            $value[2] = 'XXXX';
            $value[3] = 'XXXX';
            return implode('-', $value);
        }
        return $value;
    }
}
