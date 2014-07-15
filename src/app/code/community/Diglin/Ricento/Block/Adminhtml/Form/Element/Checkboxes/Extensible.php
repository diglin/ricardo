<?php
class Diglin_Ricento_Block_Adminhtml_Form_Element_Checkboxes_Extensible extends Varien_Data_Form_Element_Checkboxes
{

    protected function _optionToHtml($option)
    {
        if (isset($option['field'])) {
            $field = $this->addField($option['field'][0], $option['field'][1], $option['field'][2]);
            if (strpos($option['label'], '%s') !== false) {
                $option['label'] = sprintf($option['label'], $field->getElementHtml());
            } else {
                $option['label'] = $option['label'] . ' ' . $field->getElementHtml();
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
}