<?php
/**
 * Diglin GmbH - Switzerland
 * 
 * User: sylvainraye
 * Date: 07.05.14
 * Time: 00:02
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Block_Adminhtml_Products_Listing extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_products_listing';
        $this->_blockGroup = 'diglin_ricento';
        $this->_headerText = $this->__('Products Listing');
        $this->_addButtonLabel = $this->__('Create new listing');

        $this->_addButton('category_mapping', array(
                'label'     => $this->__('Category Mapping'),
                'onclick'   => 'setLocation(\'' . $this->getCategoryMapUrl() .'\')',
                'class'     => 'mapping',
            )
            ,0,1);

        parent::__construct();
    }

    public function getCategoryMapUrl()
    {
        return $this->getUrl('*/products_category/mapping');
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/edit');
    }
}