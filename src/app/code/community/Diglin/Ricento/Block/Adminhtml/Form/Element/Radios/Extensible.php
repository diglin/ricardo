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
 * Class Diglin_Ricento_Block_Adminhtml_Form_Element_Radios_Extensible
 *
 * Radio buttons that may contain additional elements beside them. Also allows disabling.
 */
class Diglin_Ricento_Block_Adminhtml_Form_Element_Radios_Extensible extends Varien_Data_Form_Element_Radios
{
    public function __construct($attributes = array())
    {
        if (!empty($attributes['types'])) {
            foreach($attributes['types'] as $type => $className) {
                $this->addType($type, $className);
            }
        }
        parent::__construct($attributes);
    }

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

    public function getElementHtml()
    {
        $html = '<ul ' . $this->serialize(array('list_class', 'list_id')) . '>';
        $html .= parent::getElementHtml();
        $html .= '</ul>';
        return $html;
    }

    protected function _optionToHtml($option, $selected)
    {
        if (isset($option['field'])) {
            $field = $this->getField($option['field']);
            if (strpos($option['label'], '%s') !== false) {
                $html = parent::_optionToHtml($option, $selected);
                return '<li>' . preg_replace_callback('#(<label [^>]*for="\w+"[^>]*>)(.*)</label>#', function($matches) use ($field) {
                    $labelTag = $matches[1]; $labelText = $matches[2];
                    return $labelTag . sprintf($labelText, '</label> ' . $field->getElementHtml() . ' ' . $labelTag) . '</label>';
                }, $html) . '</li>';
            } else {
                return '<li>' . parent::_optionToHtml($option, $selected) . ' ' . $field->getElementHtml() . '</li>';
            }
        }
        return '<li>' . parent::_optionToHtml($option, $selected) . '</li>';
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
     * Allow HTML attribute "disabled"
     *
     * @param array $attributes
     * @param string $valueSeparator
     * @param string $fieldSeparator
     * @param string $quote
     * @return string
     */
    public function serialize($attributes = array(), $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $attributes[] ='disabled';
        return parent::serialize($attributes, $valueSeparator, $fieldSeparator, $quote);
    }

}