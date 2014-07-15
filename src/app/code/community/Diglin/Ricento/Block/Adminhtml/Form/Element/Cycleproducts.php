<?php
class Diglin_Ricento_Block_Adminhtml_Form_Element_Cycleproducts extends Varien_Data_Form_Element_Abstract
{
    /**
     * @var Varien_Data_Form_Element_Radio
     */
    protected $_elementRandomNo;
    /**
     * @var Varien_Data_Form_Element_Radio
     */
    protected $_elementRandomYes;
    /**
     * @var Varien_Data_Form_Element_Text
     */
    protected $_elementMinutes;

    protected function _addChildFields()
    {
        $radioName = $this->getName() . '_random';
        $this->_elementRandomNo = $this->addField($radioName . '_no', 'radio', array(
            'name'  => $radioName,
            'value' => 0,
            'label' => Mage::helper('diglin_ricento')->__('Cycle to publish multiple products {X} minutes after the first publish')
        ));
        $this->_elementRandomYes = $this->addField($radioName . '_yes', 'radio', array(
            'name'  => $radioName,
            'value' => 1,
            'label' => Mage::helper('diglin_ricento')->__('Randomly published')
        ));
        $minutesName = $this->getName() . '_minutes';
        $this->_elementMinutes = $this->addField($minutesName, 'text', array(
            'name'    => $minutesName,
            'class'   => 'inline-number validate-number',
        ));
    }
    public function getElementHtml()
    {
        $this->_addChildFields();
        $template =
<<<HTML
<ul>
    <li>%s %s</li>
    <li>%s %s</li>
</ul>
HTML;
        return sprintf($template,
            $this->_elementRandomNo->getElementHtml(), str_replace('{X}', $this->_elementMinutes->getElementHtml(), $this->_elementRandomNo->getLabelHtml()),
            $this->_elementRandomYes->getElementHtml(), $this->_elementRandomYes->getLabelHtml());
    }
}