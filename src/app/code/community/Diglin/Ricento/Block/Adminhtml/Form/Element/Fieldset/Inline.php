<?php
class Diglin_Ricento_Block_Adminhtml_Form_Element_Fieldset_Inline extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('fieldset_inline');
    }
    public function getElementHtml()
    {
        return $this->getChildrenHtml();
    }
    public function getChildrenHtml()
    {
        $html = '';
        foreach ($this->getElements() as $element) {
            if ($element->getType() != 'fieldset') {
                $html.= $element->getElementHtml();
            }
        }
        return $html;
    }
}