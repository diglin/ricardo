<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Rules
    extends Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface

{
    protected function _initFormValues()
    {
        //TODO fill form based on rules model
        $isFreeShipping = false;
        $this->getForm()->getElement('shipping_price')->setDisabled($isFreeShipping);

        return parent::_initFormValues();
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $htmlIdPrefix = 'diglin_ricento_';
        $form->setHtmlIdPrefix($htmlIdPrefix);

        $fieldsetPayment = $form->addFieldset('fieldset_payment', array('legend' => $this->__('Payment Methods')));
        $fieldsetPayment->addField('payment_methods', 'checkboxes', array(
            'name'    => 'payment_methods',
            'label'   => $this->__('Select Payment Methods'),
            'values' => Mage::getModel('diglin_ricento/config_source_rules_payment')->getAllOptions()
        ));
        $fieldsetPayment->addField('payment_methods_description', 'textarea', array(
            'name'  => 'payment_methods_description',
            'label' => $this->__('Payment information to display to customers')
        ));
        $fieldsetShipping = $form->addFieldset('fieldset_shipping', array('legend' => $this->__('Shipping Methods')));
        $fieldsetShipping->addField('shipping_methods', 'select', array(
            'name'    => 'shipping_methods',
            'label'   => $this->__('Shipping Methods'),
            'values'  => Mage::getModel('diglin_ricento/config_source_rules_shipping')->getAllOptions()
        ));
        $fieldsetShipping->addField('shipping_availability', 'select', array(
            'name'    => 'shipping_availability',
            'label'   => $this->__('Availability'),
            'values'  => Mage::getModel('diglin_ricento/config_source_rules_shipping_availability')->getAllOptions()
        ));
        $fieldsetShipping->addField('shipping_description', 'textarea', array(
            'name'  => 'shipping_description',
            'label' => $this->__('Description')
        ));
        $fieldsetShipping->addField('shipping_price', 'text', array(
            'name'     => 'shipping_price',
            'label'    => $this->__('Price'),
        ));
        $fieldsetShipping->addField('free_shipping', 'select', array(
            'name'    => 'free_shipping',
            'label'   => $this->__('Free shipping'),
            'values'  => Mage::getModel('eav/entity_attribute_source_boolean')->getAllOptions(),
            'onclick' => '$(\'diglin_ricento_shipping_price\').disabled = this.checked'
        ));
        $this->setForm($form);

        return parent::_prepareForm();
    }
    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Rules');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Rules');
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