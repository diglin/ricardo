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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Rules
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Rules
    extends Diglin_Ricento_Block_Adminhtml_Products_Listing_Form_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * @var Diglin_Ricento_Model_Rule
     */
    protected $_model;

    /**
     * Returns sales options model
     *
     * @return Diglin_Ricento_Model_Rule
     */
    public function getShippingPaymentRule()
    {
        if (!$this->_model) {
            $this->_model = $this->_getListing()->getShippingPaymentRule();
            $data = Mage::getSingleton('adminhtml/session')->getRulesFormData(true);
            if (!empty($data)) {
                $this->_model->setData($data);
            }
        }
        return $this->_model;
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $htmlIdPrefix = 'rules_';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        $form->addField('rule_id', 'hidden', array(
            'name' => 'rules[rule_id]',
        ));

        $fieldsetPayment = $form->addFieldset('fieldset_payment', array('legend' => $this->__('Payment Methods')));
        $fieldsetPayment->addType('checkboxes_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_checkboxes_extensible'));
        $fieldsetPayment->addField('payment_methods', 'checkboxes_extensible', array(
            'name'    => 'rules[payment_methods][]',
            'label'   => $this->__('Payment Methods'),
            'values'  => Mage::getModel('diglin_ricento/config_source_rules_payment')->getAllOptions(),
            'class'   => 'validate-payment-method-combination',
            'onchange' => 'rulesForm.togglePaymentDescription($(\'' . $htmlIdPrefix . 'payment_methods_0\'));'
        ));
        $fieldsetPayment->addField('payment_description', 'textarea', array(
            'name'  => 'rules[payment_description]',
            'label' => $this->__('Payment description'),
            'note' => $this->__('Payment information to display to customers. Will be send to ricardo only if you select the method "Other"')
        ));

        $fieldsetShipping = $form->addFieldset('fieldset_shipping', array('legend' => $this->__('Shipping Methods')));
        $fieldsetShipping->addField('shipping_method', 'select', array(
            'name'    => 'rules[shipping_method]',
            'label'   => $this->__('Shipping Methods'),
            'values'  => Mage::getModel('diglin_ricento/config_source_rules_shipping')->getAllOptions(),
            'class'   => 'validate-payment-shipping-combination',
            'onchange' => 'rulesForm.toggleShippingDescription(this);'
        ));
        $fieldsetShipping->addField('shipping_availability', 'select', array(
            'name'    => 'rules[shipping_availability]',
            'label'   => $this->__('Shipping Availability'),
            'values'  => Mage::getModel('diglin_ricento/config_source_rules_shipping_availability')->getAllOptions()
        ));
        $fieldsetShipping->addField('shipping_description', 'textarea', array(
            'name'  => 'rules[shipping_description]',
            'label' => $this->__('Shipping Description'),
            'required' => true
        ));
        $fieldsetShipping->addField('shipping_price', 'text', array(
            'name'     => 'rules[shipping_price]',
            'label'    => $this->__('Shipping Price'),
            'class'    => 'validate-number',
            'required' => true
        ));
        $fieldsetShipping->addField('free_shipping', 'checkbox', array(
            'name'    => 'rules[free_shipping]',
            'label'   => $this->__('Free shipping'),
            'onclick' => 'rulesForm.switchShippingPrice(this);'
        ));
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _initFormValues()
    {
        $this->getForm()->setValues($this->getShippingPaymentRule());

        $shippingPrice = $this->getShippingPaymentRule()->getShippingPrice();
        $isFreeShipping = (!is_null($shippingPrice) && $shippingPrice == 0) ? true : false;
        $this->getForm()->getElement('shipping_price')->setDisabled($isFreeShipping);
        $this->getForm()->getElement('free_shipping')->setChecked($isFreeShipping);

        $disableDescription = (!$this->getShippingPaymentRule()->getShippingMethod()) ? false : true;
        $this->getForm()->getElement('shipping_description')
            ->setDisabled($disableDescription)
            ->setRequired(!$disableDescription);

        $disablePaymentDescription = (!$this->getShippingPaymentRule()->getPaymentMethods()) ? false : true;
        $this->getForm()->getElement('payment_description')
            ->setDisabled($disablePaymentDescription)
            ->setRequired(!$disablePaymentDescription);

        $derivedValues = array();
        $derivedValues['free_shipping'] = 1;

        $this->getForm()->addValues($derivedValues);
        return parent::_initFormValues();
    }

    /**
     * Improve the display of some html form elements
     *
     * @return Mage_Adminhtml_Block_Widget_Form|void
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $form = $this->getForm();
        $fieldsetShipping = $form->getElement('fieldset_shipping');

        $elements = $fieldsetShipping->getElements();

        $fieldShippingPrice = $elements->searchById('shipping_price');
        $fieldFreeShipping = $elements->searchById('free_shipping');

        if($fieldFreeShipping){
            $fieldShippingPrice->setData('after_element_html', Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY . ' - ' . $fieldFreeShipping->getLabelHtml() . $fieldFreeShipping->getElementHtml() );
            $fieldsetShipping->removeField('free_shipping');
        }
    }

    protected function _afterToHtml($html)
    {
        $html .= '<script type="text/javascript">var rulesForm = new Ricento.RulesForm("' . $this->getForm()->getHtmlIdPrefix() . '");' . Mage::getModel('diglin_ricento/rule_validate')->getJavaScriptValidator() . '</script>';
        return parent::_afterToHtml($html);
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