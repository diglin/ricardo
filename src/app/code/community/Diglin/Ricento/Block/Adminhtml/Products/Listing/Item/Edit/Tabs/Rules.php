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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit_Tabs_Rules
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit_Tabs_Rules
    extends Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Rules
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
            if ($item->getRuleId()) {
                $useDefaultCheckbox->setChecked(false);
                break;
            }
        }
        if ($useDefaultCheckbox->getChecked()) {
            Mage::helper('diglin_ricento')->disableForm($this->getForm());
            $useDefaultCheckbox->setDisabled(false);
            $this->getForm()->getElement('free_shipping')->setChecked(false);
        }
        return $this;
    }

    protected function _prepareForm()
    {
        parent::_prepareForm();
        $this->getForm()->addField('use_products_list_settings', 'checkbox', array(
            'name' => 'rules[use_products_list_settings]',
            'label' => 'Use Product List Settings',
            'onclick' => "Ricento.useProductListSettings(this, '{$this->getForm()->getHtmlIdPrefix()}')"
        ), '^');
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
     * @return Diglin_Ricento_Model_Rule
     */
    public function getShippingPaymentRule()
    {
        if (!$this->_model) {
            if (count($this->getSelectedItems()) === 1) {
                $this->_loadSalesOptionsFromItem($this->getSelectedItems()->getFirstItem());
            }
            if (!$this->_model) {
                $this->_model = $this->_getListing()->getSalesOptions();
                $this->_model->unsetData('rule_id');
            }
            $data = Mage::getSingleton('adminhtml/session')->getRulesFormData(true);
            if (!empty($data)) {
                $this->_model->setData($data);
            }
        }
        return $this->_model;
    }

    /**
     * @param Diglin_Ricento_Model_Products_Listing_Item $item
     */
    protected function _loadSalesOptionsFromItem(Diglin_Ricento_Model_Products_Listing_Item $item)
    {
        if ($item->getRuleId()) {
            $this->_model = $item->getShippingPaymentRule();
        }
    }
}