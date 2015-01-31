<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Grid
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('listingGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    public function getListing()
    {
        return Mage::registry('products_listing');
    }

    /**
     * Prepare the collection to be displayed as a grid
     *
     * @return Varien_Data_Collection
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('diglin_ricento/products_listing_collection');
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
        $helper = Mage::helper('diglin_ricento');
        
        $this->addColumn('entity_id', array(
            'header' => $this->__('ID') ,
            'align' => 'left',
            'index' => 'entity_id',
            'type' => 'number',
            'width' => '50px',
        ));

        $this->addColumn('title', array(
            'header' => $this->__('Title') ,
            'align' => 'left',
            'index' => 'title',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites',
                array(
                    'header'=> Mage::helper('catalog')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'website_id',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
                ));
        }

        $this->addColumn('total', array(
            'header' => $this->__('Total') ,
            'align' => 'right',
            'width' => '50px',
            'index' => 'total',
            'type' => 'number',
            'renderer' => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_grid_renderer_total')
        ));

        $this->addColumn('status', array(
            'header'   => $this->__('Status') ,
            'align'    => 'left',
            'width'    => '150px',
            'index'    => 'status',
            'type'     => 'options',
            'options'  => Mage::getSingleton('diglin_ricento/config_source_status')->toOptionHash(),
            'renderer' => Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_products_listing_grid_renderer_status')
        ));

        $this->addColumn('action',
            array(
                'header'    => $helper->__('Action'),
                'width'     => '90px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => $helper->__('Edit'),
                        'url'     => array(
                            'base'=>'*/*/edit',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    ),
                    array(
                        'caption' => $helper->__('List'),
                        'url'     => array(
                            'base'=>'*/*/list',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    ),
//                    array(
//                        'caption' => $helper->__('Relist'),
//                        'url'     => array(
//                            'base'=>'*/*/relist',
//                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
//                        ),
//                        'field'   => 'id'
//                    ),
                    array(
                        'caption' => $helper->__('Stop List'),
                        'url'     => array(
                            'base'=>'*/*/stop',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    ),
                    array(
                        'caption' => $helper->__('View Logs'),
                        'url'     => array(
                            'base'=>'*/log/listing',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
            ));

    }

    /**
     * Prepare the mass action drop down menu
     *
     * @return Diglin_Ricento_Block_Adminhtml_Products_Listing_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('products_listing');

// Changing status it's quite a bad idea at product listing level, better at product listing item level
//        $statuses = Mage::getSingleton('diglin_ricento/config_source_status')->toOptionArray();
//
//        // Remove the first and last item cause no need here
//        array_unshift($statuses, array('label'=>'', 'value'=>''));
//        array_pop($statuses);

//        $this->getMassactionBlock()->addItem('status', array(
//            'label'=> $this->__('Change status'),
//            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
//            'additional' => array(
//                'visibility' => array(
//                    'name' => 'status',
//                    'type' => 'select',
//                    'class' => 'required-entry',
//                    'label' => $this->__('Status'),
//                    'values' => $statuses
//                )
//            )
//        ));

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> $this->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete', array('_current'=>true)),
            'confirm' => $this->__('Are you sure that you want to delete this/these products listing(s)? Be aware it\'s only possible when the listing is "Listed"')
        ));

        return $this;
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }
    /**
     * Get the url to set for each row in the grid
     *
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'id' => $row->getId()
        ));
    }
}