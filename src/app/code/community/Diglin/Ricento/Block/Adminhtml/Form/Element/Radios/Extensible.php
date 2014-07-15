<?php
class Diglin_Ricento_Block_Adminhtml_Form_Element_Radios_Extensible extends Varien_Data_Form_Element_Radios
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
                $option['label'] = sprintf($option['label'], $field->getElementHtml());
            } else {
                $option['label'] = $option['label'] . ' ' . $field->getElementHtml();
            }
        }
        $html = '<li>';
        $html .= parent::_optionToHtml($option, $selected);
        $html .= '</li>';
        return $html;
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
}