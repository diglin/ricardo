<?php
class Diglin_Ricento_Block_Adminhtml_Form_Element_Radios_Extensible extends Varien_Data_Form_Element_Radios
{
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
            $field = $this->addField($option['field'][0], $option['field'][1], $option['field'][2]);
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

}