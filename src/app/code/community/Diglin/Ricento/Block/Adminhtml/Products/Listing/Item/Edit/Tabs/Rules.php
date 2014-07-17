<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit_Tabs_Rules
    extends Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Rules
{
    protected function _initFormValues()
    {
        parent::_initFormValues();
        return $this;
    }

    protected function _prepareForm()
    {
        parent::_prepareForm();
        $this->getForm()->addField('use_products_list_settings', 'checkbox', array(
            'name'    => 'rules[use_product_list_settings]',
            'label'   => 'Use Product List Settings',
            'onclick' => "var self = this; this.form.getElements().each(function(element) { if (element!=self && element.id.startsWith('{$this->getForm()->getHtmlIdPrefix()}')) element.disabled=self.checked; })"
        ), '^');
        return $this;
    }
}