<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit_Tabs_Selloptions
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit_Tabs_Selloptions
    extends Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Selloptions

{
    /**
     * @return bool
     */
    public function isReadonlyForm()
    {
        foreach ($this->getSelectedItems() as $item) {
            /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
            if ($item->getStatus() === Diglin_Ricento_Helper_Data::STATUS_LISTED) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getReadonlyNote()
    {
        return $this->__('Listed items cannot be modified. Stop the listing first to make changes.');
    }

    /**
     * @return $this|Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $this->getForm()->addField('use_products_list_settings', 'checkbox', array(
            'name'    => 'sales_options[use_products_list_settings]',
            'label'   => 'Use Product List Settings',
            'onclick' => "var self = this; this.form.getElements().each(function(element) { if (element!=self && element.id.startsWith('{$this->getForm()->getHtmlIdPrefix()}')) { element.disabled=self.checked; self.checked ? element.addClassName('disabled') : element.removeClassName('disabled');} })"
        ), '^');
        return $this;
    }

    /**
     * If all items use the default list settings, check the "use default" checkbox
     * and disable all elements but the checkbox
     *
     * @return $this|Mage_Adminhtml_Block_Widget_Form
     */
    protected function _initFormValues()
    {
        parent::_initFormValues();
        $useDefaultCheckbox = $this->getForm()->getElement('use_products_list_settings');
        $useDefaultCheckbox->setChecked(true);
        foreach ($this->getSelectedItems() as $item) {
            /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
            if ($item->getSalesOptionsId()) {
                $useDefaultCheckbox->setChecked(false);
                break;
            }
        }
        if ($useDefaultCheckbox->getChecked()) {
            Mage::helper('diglin_ricento')->disableForm($this->getForm());
            $useDefaultCheckbox->setDisabled(false);
        }
        return $this;
    }

    /**
     * Returns items that are selected to be configured
     *
     * @return Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection
     */
    public function getSelectedItems()
    {
        return Mage::registry('selected_items');
    }

    /**
     * Returns sales options model. Use sales options from single item if there is only one item
     * and it has individual settings. Otherwise use a copy of the sales options from listing
     *
     * @return Diglin_Ricento_Model_Sales_Options
     */
    public function getSalesOptions()
    {
        if (!$this->_model) {
            if (count($this->getSelectedItems()) === 1) {
                $this->_loadSalesOptionsFromItem($this->getSelectedItems()->getFirstItem());
            }
            if (!$this->_model) {
                $this->_model = $this->_getListing()->getSalesOptions();
                $this->_model->unsetData('entity_id');
            }
        }
        return $this->_model;
    }

    protected function _loadSalesOptionsFromItem(Diglin_Ricento_Model_Products_Listing_Item $item)
    {
        if ($item->getSalesOptionsId()) {
            $this->_model = $item->getSalesOptions();
        }
    }
}