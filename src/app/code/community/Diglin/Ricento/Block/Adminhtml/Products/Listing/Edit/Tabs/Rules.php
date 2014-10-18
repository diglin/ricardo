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
            'name' => 'rules[payment_methods][]',
            'label' => $this->__('Payment Methods'),
            'values' => Mage::getSingleton('diglin_ricento/config_source_rules_payment')->getAllOptions(),
            'class' => 'validate-payment-method-combination',
            'note' => $this->__('Combination possible: Cash, Bank transfer / Post, Other, Credit Card + Other, Credit Card + Bank transfer or Credit Card + Cash'),
            'onchange' => 'rulesForm.togglePaymentDescription($(\''
                . $htmlIdPrefix . 'payment_methods_'
                . \Diglin\Ricardo\Enums\PaymentMethods::TYPE_OTHER . '\')); rulesForm.toggleStartPrice($(\'sales_options_sales_auction_start_price\'), \''
                . \Diglin\Ricardo\Enums\PaymentMethods::TYPE_CREDIT_CARD . '\');'
        ));
        $fieldsetPayment->addField('payment_description_de', 'textarea', array(
            'name' => 'rules[payment_description_de]',
            'label' => $this->__('Payment Description German'),
            'note' => $this->__('Characters: %s. Max. 5 000 characters. Payment information to display to customers. Will be send to ricardo only if you select the method "Other"',
                    $this->getCountableText($htmlIdPrefix . 'payment_description_de')),
            'class' => 'validate-length maximum-length-5000'
        ));
        $fieldsetPayment->addField('payment_description_fr', 'textarea', array(
            'name' => 'rules[payment_description_fr]',
            'label' => $this->__('Payment Description French'),
            'note' => $this->__('Characters: %s. Max. 5 000 characters. Payment information to display to customers. Will be send to ricardo only if you select the method "Other"',
                    $this->getCountableText($htmlIdPrefix . 'payment_description_fr')),
            'class' => 'validate-length maximum-length-5000'
        ));

        $fieldsetShipping = $form->addFieldset('fieldset_shipping', array('legend' => $this->__('Shipping Methods')));
        $fieldsetShipping->addField('shipping_method', 'select', array(
            'name' => 'rules[shipping_method]',
            'label' => $this->__('Shipping Methods'),
            'values' => Mage::getSingleton('diglin_ricento/config_source_rules_shipping')->getAllOptions(),
            'class' => 'validate-payment-shipping-combination',
            'onchange' => 'rulesForm.toggleShippingDescription(this); rulesForm.initPackages(this);'
        ));
        $fieldsetShipping->addField('shipping_package', 'select', array(
            'name' => 'rules[shipping_package]',
            'class' => '',
            'onchange' => 'rulesForm.setShippingFee(this);'
        ));
        $fieldsetShipping->addField('shipping_price', 'text', array(
            'name' => 'rules[shipping_price]',
            'label' => $this->__('Shipping Price'),
            'class' => 'validate-number',
            'required' => true
        ));
//        $fieldsetShipping->addField('free_shipping', 'checkbox', array(
//            'name'    => 'rules[free_shipping]',
//            'label'   => $this->__('Free shipping'),
//            'onclick' => 'rulesForm.switchShippingPrice(this);'
//        ));
        $fieldsetShipping->addField('shipping_cumulative_fee', 'checkbox', array(
            'name' => 'rules[shipping_cumulative_fee]',
            'label' => $this->__('Is Shipping fee cumulative'),
            'note' => $this->__('If you select this option, the shipping fee will be calculate for each sold product.')
                . $this->__('e.g. an article is sold with a quantity of 3 to one customer and the shipping fee is 15 CHF. Total Shipping fee is 45 CHF. Let it empty if you don\'t such an option.')
        ));
        $fieldsetShipping->addField('shipping_availability', 'select', array(
            'name' => 'rules[shipping_availability]',
            'label' => $this->__('Shipping Availability'),
            'values' => Mage::getSingleton('diglin_ricento/config_source_rules_shipping_availability')->getAllOptions()
        ));
        $fieldsetShipping->addField('shipping_description_de', 'textarea', array(
            'name' => 'rules[shipping_description_de]',
            'label' => $this->__('Shipping Description German'),
            'required' => true,
            'class' => 'validate-length maximum-length-5000',
            'note' => $this->__('Characters: %s. Max. 5 000 characters', $this->getCountableText($htmlIdPrefix . 'shipping_description_de'))
        ));
        $fieldsetShipping->addField('shipping_description_fr', 'textarea', array(
            'name' => 'rules[shipping_description_fr]',
            'label' => $this->__('Shipping Description French'),
            'required' => true,
            'class' => 'validate-length maximum-length-5000',
            'note' => $this->__('Characters: %s. Max. 5 000 characters', $this->getCountableText($htmlIdPrefix . 'shipping_description_fr'))
        ));
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _initFormValues()
    {
        $supportedLangs = Mage::helper('diglin_ricento')->getSupportedLang();

        $this->getForm()->setValues($this->getShippingPaymentRule());
        $this->getForm()->getElement('shipping_cumulative_fee')->setChecked((bool)$this->getShippingPaymentRule()->getShippingCumulativeFee());

        $disableDescription = (!$this->getShippingPaymentRule()->getShippingMethod()) ? false : true;

        $disablePaymentDescription = true;
        $methods = (array)$this->getShippingPaymentRule()->getPaymentMethods();
        foreach ($methods as $method) {
            if ((int)$method === 0) {
                $disablePaymentDescription = false;
                break;
            }
        }

        foreach ($supportedLangs as $supportedLang) {
            $supportedLang = strtolower($supportedLang);

            $this->getForm()->getElement('shipping_description_' . $supportedLang)
                ->setDisabled($disableDescription)
                ->setRequired(!$disableDescription);
            $this->getForm()->getElement('payment_description_' . $supportedLang)
                ->setDisabled($disablePaymentDescription)
                ->setRequired(!$disablePaymentDescription);
        }

        $derivedValues = array();
        $derivedValues['shipping_cumulative_fee'] = 1;

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
        $fieldShippingPrice->setData('after_element_html', Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY);

        $fieldPackages = $elements->searchById('shipping_package');
        $fieldMethod = $elements->searchById('shipping_method');

        $fieldMethod->setData('after_element_html', $fieldPackages->getElementHtml());
        $fieldsetShipping->removeField('shipping_package');
    }

    protected function _afterToHtml($html)
    {
        $shippingPackage = ($this->getShippingPaymentRule()->getShippingPackage()) ? ',' . $this->getShippingPaymentRule()->getShippingPackage() : '';
        $html .= '<script>var rulesForm = new Ricento.RulesForm("' . $this->getForm()->getHtmlIdPrefix() . '", "'
            . Mage::helper('core')->jsQuoteEscape(json_encode(Mage::getSingleton('diglin_ricento/config_source_rules_shipping_packages')->toOptionHash()), "\"") . '");';
        $html .= 'rulesForm.initPackages($(\'' . $this->getForm()->getHtmlIdPrefix() . 'shipping_method\') ' . $shippingPackage . ');';
        $html .= '</script>';
        $html .= Mage::getModel('diglin_ricento/validate_rules_methods')->getJavaScriptValidator();
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