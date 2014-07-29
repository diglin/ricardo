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
 * Class Diglin_Ricento_Block_Adminhtml_Form_Element_Checkboxes_Extensible
 *
 * Checkboxes that may contain additional elements beside them. Also allows disabling of all checkboxes
 *
 */
class Diglin_Ricento_Block_Adminhtml_Form_Element_Checkboxes_Extensible extends Varien_Data_Form_Element_Checkboxes
{
    public function setForm($form)
    {
        parent::setForm($form);
        /*
         * Pre-generate HTML once to add children
         */
        $this->getElementHtml();
        foreach ($this->getElements() as $_element) {
            $_element->setForm($form);
        }
        return $this;
    }
    protected function _optionToHtml($option)
    {
        if (isset($option['field'])) {
            $field = $this->getField($option['field']);
            if (strpos($option['label'], '%s') !== false) {
                $html = parent::_optionToHtml($option);
                return preg_replace_callback('#(<label [^>]*for="\w+"[^>]*>)(.*)</label>#', function($matches) use ($field) {
                    $labelTag = $matches[1]; $labelText = $matches[2];
                    return $labelTag . sprintf($labelText, '</label> ' . $field->getElementHtml() . ' ' . $labelTag) . '</label>';
                }, $html);
            } else {
                $html = parent::_optionToHtml($option);
                return str_replace('</li>', ' ' . $field->getElementHtml() . '</li>',$html);
            }
        }
        return parent::_optionToHtml($option);
    }

    protected function _prepareValues()
    {
        /*
         * No conversions, to maintain 'field' in values array
         */
        return $this->getValues();
    }

    /**
     * Returns child field, creates it based on definition if not exists
     *
     * @param $fieldDefinition
     * @return null|Varien_Data_Form_Element_Abstract
     */
    protected function getField($fieldDefinition)
    {
        $field = $this->getElements()->searchById($fieldDefinition[0]);
        if (!$field) {
            $field = $this->addField($fieldDefinition[0], $fieldDefinition[1], $fieldDefinition[2]);
        }
        return $field;
    }

    /**
     * Allow disabling of all checkboxes with disabled=true
     *
     * @param $value
     * @return string
     */
    public function getDisabled($value)
    {
        if ($this->getData('disabled') === true) {
            return 'disabled';
        }
        return parent::getDisabled($value);
    }

}