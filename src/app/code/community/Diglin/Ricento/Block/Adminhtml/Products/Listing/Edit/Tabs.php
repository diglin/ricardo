<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('product_listing_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Product Listing'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('products_section', array(
            'label' => $this->__('Products') ,
            'title' => $this->__('Products') ,
            'content' => $this->getLayout()
                    ->createBlock('diglin_ricento/adminhtml_product_listing_edit_tabs_products')
                    ->toHtml()
        ));

        $this->addTab('rules_section', array(
            'label' => $this->__('Rules') ,
            'title' => $this->__('Rules') ,
            'content' => $this->getLayout()
                    ->createBlock('diglin_ricento/adminhtml_product_listing_edit_tabs_rules')
                    ->toHtml()
        ));

        $this->addTab('selloptions_section', array(
            'label' => $this->__('Sell Options') ,
            'title' => $this->__('Sell Options') ,
            'content' => $this->getLayout()
                    ->createBlock('diglin_ricento/adminhtml_product_listing_edit_tabs_selloptions')
                    ->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}