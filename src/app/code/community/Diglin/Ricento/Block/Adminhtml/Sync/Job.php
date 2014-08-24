<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Sync_Job extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_sync_job';
        $this->_blockGroup = 'diglin_ricento';
        $this->_headerText = $this->__('Synchronization Jobs');
        //$this->_addButtonLabel = $this->__('Create new listing');

        $this->addButton('forward', array(
            'label' => $this->__('Go to Products Listing'),
            'onclick' => 'setLocation(\''.$this->getUrl('*/products_listing/index').'\')',
        ));

        parent::__construct();

        $this->removeButton('add');
        //$this->_updateButton('add', 'onclick', 'Ricento.newListingPopup()');
    }

    public function getCreateUrl()
    {
        //return $this->getUrl('*/*/edit');
        return '#';
    }
}