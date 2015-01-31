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

        $helper = Mage::helper('diglin_ricento');

        $useDefaultCheckbox = $this->getForm()->getElement('use_products_list_settings');
        $useDefaultCheckbox->setChecked(true);
        $useDefaultCheckbox->setValue(1);

        foreach ($this->getSelectedItems() as $item) {
            /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
            if ($item->getRuleId()) {
                $useDefaultCheckbox->setChecked(false);
                break;
            }
        }
        if ($useDefaultCheckbox->getChecked()) {
            $helper->disableForm($this->getForm());
            $useDefaultCheckbox->setDisabled(false);
        }

        $publishedLang = $this->_getListing()->getPublishLanguages();
        if ($publishedLang != Diglin_Ricento_Helper_Data::LANG_ALL) {
            $supportedLangs = $helper->getSupportedLang();
            foreach ($supportedLangs as $supportedLang) {
                $supportedLang = strtolower($supportedLang);
                if ($supportedLang != $publishedLang) {
                    $this->getForm()->getElement('fieldset_shipping')
                        ->removeField('shipping_description_' . $supportedLang);

                    $this->getForm()->getElement('fieldset_payment')
                        ->removeField('payment_description_' . $supportedLang);
                }
            }
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