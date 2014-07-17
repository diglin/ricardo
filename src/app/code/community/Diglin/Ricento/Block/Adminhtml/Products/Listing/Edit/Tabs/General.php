<?php
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_General
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    protected function _getListing()
    {
        $listing = Mage::registry('products_listing');
        if (!$listing) {
            Mage::throwException('Products listing not loaded');
        }
        return $listing;
    }
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $htmlIdPrefix = 'product_listing_';
        $form->setHtmlIdPrefix($htmlIdPrefix);

        $fieldsetMain = $form->addFieldset('fieldset_main', array('legend' => $this->__('General')));
        $fieldsetMain->addField('entity_id', 'hidden', array(
            'name' => 'product_listing[entity_id]',
        ));
        $fieldsetMain->addField('title', 'text', array(
            'name'  => 'product_listing[title]',
            'label' => $this->__('Title')
        ));
        $fieldsetMain->addField('status', 'select', array(
            'label'    => $this->__('Status'),
            'disabled' => true,
            'values'   => Mage::getModel('diglin_ricento/config_source_status')->getAllOptions()
        ));
        $fieldsetMain->addField('store_id', 'select', array(
            'label'    => $this->__('Store View'),
            'disabled' => true,
            'values'   => Mage::getSingleton('adminhtml/system_store')
                    ->getStoreValuesForForm(true, false)
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _initFormValues()
    {
        $this->getForm()->setValues($this->_getListing());

        return parent::_initFormValues();
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('General');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('General');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}