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
 * Class Diglin_Ricento_Model_Rule_Validate
 */
class Diglin_Ricento_Model_Rule_Validate extends Zend_Validate_Abstract
{
    const ERROR_INVALID_PAYMENT_COMBINATION = 'invalidPaymentCombination';
    const ERROR_INVALID_PAYMENT_SHIPPING_COMBINATION = 'invalidPaymentShippingCombination';

    protected $_allowedPaymentCombinations = array(
        array(
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_CREDIT_CARD,
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_BANK_TRANSFER),
        array(
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_CREDIT_CARD,
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_CASH),
        array(
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_CREDIT_CARD,
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_OTHER),
        array(
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_BANK_TRANSFER),
        array(
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_CASH),
        array(
            Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_OTHER)
    );
    protected $_disallowedPaymentShippingCombinations = array(
        array(
            'shipping' => Diglin_Ricento_Model_Config_Source_Rules_Shipping::TYPE_OTHER,
            'payment'  => Diglin_Ricento_Model_Config_Source_Rules_Payment::TYPE_CREDIT_CARD)
    );

    protected $_messageTemplates = array(
        //TODO translatable error messages
        self::ERROR_INVALID_PAYMENT_COMBINATION          => '',
        self::ERROR_INVALID_PAYMENT_SHIPPING_COMBINATION => ''
    );

    public function __construct()
    {
        $this->_normalizeAllowedPaymentCombinations();
    }
    protected function _normalizeAllowedPaymentCombinations()
    {
        foreach ($this->_allowedPaymentCombinations as &$paymentArray) {
            sort($paymentArray);
        }
    }
    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid($value)
    {
        if (!is_array($value) || !isset($value['shipping']) || !isset($value['payment'])) {
            throw new Zend_Validate_Exception(__CLASS__ . ' expects array with keys "payment" and "shipping"');
        }
        $this->_setValue($value);
        return $this->_isValidPaymentCombination($value) && $this->_isValidPaymentShippingCombination($value);
    }

    /**
     * Returns true if the combination of payment methods is allowed
     *
     * @param mixed $value [ 'shipping' => int, 'payment' => int[] ]
     * @return bool
     */
    protected function _isValidPaymentCombination($value)
    {
        $normalizedPaymentArray = array_values((array) $value['payment']);
        sort($normalizedPaymentArray);
        if (!in_array($normalizedPaymentArray, $this->_allowedPaymentCombinations)) {
            $this->_error(self::ERROR_INVALID_PAYMENT_COMBINATION);
            return false;
        }
        return true;
    }
    /**
     * Returns true if the selected shipping method is allowed together with the selected payment methods
     *
     * @param mixed $value [ 'shipping' => int, 'payment' => int[] ]
     * @return bool
     */
    protected function _isValidPaymentShippingCombination($value)
    {
        foreach ($this->_disallowedPaymentShippingCombinations as $disallowedCombination) {
            if ($disallowedCombination['shipping'] === $value['shipping'] && in_array($disallowedCombination['payment'], $value['payment'])) {
                $this->_error(self::ERROR_INVALID_PAYMENT_SHIPPING_COMBINATION);
                return false;
            }
        }
        return true;
    }

    /**
     * Returns JavaScript with form validation methods based on $_allowedPaymentCombinations and $disallowedPaymentShippingCombinations
     *
     * @return string
     */
    public function getJavaScriptValidator()
    {
        $helper = Mage::helper('diglin_ricento');
        $jsonAllowedPaymentCombinations = Mage::helper('core')->jsonEncode($this->_allowedPaymentCombinations);
        $paymentValidationMessage = $helper->__('This combination is not possible.') .
            '<ul class="messages"><li class="notice-msg">' . $helper->__('The following combinations are possible:') .
            $this->_htmlListOfAllowedPaymentCombinations() .
            '</li></ul>';
        $jsonDisallowedPaymentShippingCombinations = Mage::helper('core')->jsonEncode($this->_disallowedPaymentShippingCombinations);
        $paymentShippingValidationMessage = $helper->__('It is not possible to combine "Other" with "Credit Card" payment method');
        return
<<<JS
Validation.add('validate-payment-method-combination', '{$paymentValidationMessage}', function(fieldValue, field) {
    var checkboxes = field.form[field.name];
    var paymentValue = [];
    for (var i = 0; i < checkboxes.length; ++i) {
        if (checkboxes[i].checked) {
            paymentValue.push(parseInt(checkboxes[i].value));
        }
    }
    var allowedPaymentCombinations = {$jsonAllowedPaymentCombinations};
    var arraysAreEqual = function(a1, a2) {
        return a1.length==a2.length && a1.every(function(v,i) { return a2.indexOf(v) >= 0});
    };
    for (var i = 0; i < allowedPaymentCombinations.length; ++i) {
        if (arraysAreEqual(allowedPaymentCombinations[i], paymentValue)) {
            return true;
        }
    }
    return false;
});
var paymentFormFieldName = 'rules[payment_methods][]';
Validation.add('validate-payment-shipping-combination', '{$paymentShippingValidationMessage}', function(fieldValue, field) {
    var checkboxes = field.form[paymentFormFieldName]
    var paymentValue = [];
    for (var i = 0; i < checkboxes.length; ++i) {
        if (checkboxes[i].checked) {
            paymentValue.push(parseInt(checkboxes[i].value));
        }
    }
    var disallowedPaymentShippingCombinations = {$jsonDisallowedPaymentShippingCombinations};
    for (var i = 0; i < disallowedPaymentShippingCombinations.length; ++i) {
        if (disallowedPaymentShippingCombinations[i].shipping == fieldValue && paymentValue.indexOf(disallowedPaymentShippingCombinations[i].payment) >= 0) {
            return false;
        }
    }
    return true;
});
JS;

    }

    /**
     * Returns HTML for message box with detailed validation info
     *
     * @return string one line of HTML
     */
    protected function _htmlListOfAllowedPaymentCombinations()
    {
        /* @var $source Diglin_Ricento_Model_Config_Source_Rules_Payment */
        $source = Mage::getModel('diglin_ricento/config_source_rules_payment');

        $html = '<ul class="allowed-payment-combinations">';
        foreach ($this->_allowedPaymentCombinations as $paymentCombination) {
            $html .= '<li>';
            $html .= join(' <em>+</em> ', array_map(array($source, 'getOptionText'), $paymentCombination));
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}