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

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_products_listing';
        $this->_blockGroup = 'diglin_ricento';
        $this->_headerText = $this->__('Products Listing');
        $this->_addButtonLabel = $this->__('Create new listing');

        $this->addButton('sync_job', array(
            'label' => $this->__('Show Synchronization Jobs'),
            'onclick' => 'setLocation(\''.$this->getUrl('*/log/sync').'\')',
        ));

        parent::__construct();
        $this->_updateButton('add', 'onclick', 'Ricento.newListingPopup()');
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/edit');
    }
}