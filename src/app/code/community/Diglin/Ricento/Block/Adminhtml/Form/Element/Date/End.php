<?php
class Diglin_Ricento_Block_Adminhtml_Form_Element_Date_End extends Varien_Data_Form_Element_Abstract
{
    /**
     * @var Varien_Data_Form_Element_Radio
     */
    protected $_elementUseDateNo;
    /**
     * @var Varien_Data_Form_Element_Radio
     */
    protected $_elementUseDateYes;
    /**
     * @var Varien_Data_Form_Element_Select
     */
    protected $_elementDays;
    /**
     * @var Varien_Data_Form_Element_Date
     */
    protected $_elementDate;

    protected function _addChildFields()
    {
        $radioName = $this->getName() . '_use_date';
        $this->_elementUseDateNo = $this->addField($radioName . '_no', 'radio', array(
            'name'  => $radioName,
            'value' => 0,
            'label' => Mage::helper('diglin_ricento')->__('End after {X} days')
        ));
        $this->_elementUseDateYes = $this->addField($radioName . '_yes', 'radio', array(
            'name'  => $radioName,
            'value' => 1,
            'label' => Mage::helper('diglin_ricento')->__('End on')
        ));
        $daysName = $this->getName() . '_days';
        $this->_elementDays = $this->addField($daysName, 'select', array(
            'name'    => $daysName,
            'options' => $this->_getDaysOptions(),
            'class'   => 'inline-select'
        ));
        $dateName = $this->getName() . '_date';
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );
        $this->_elementDate = $this->addField($dateName, 'date', array(
            'name'   => $dateName,
            'image'     => Mage::getDesign()->getSkinUrl('images/grid-cal.gif'),
            'format'    => $dateFormatIso,
            'class'     => 'validate-date validate-date-range' //TODO concrete validation
        ));
    }
    public function getElementHtml()
    {
        $this->_addChildFields();
        $template =
<<<HTML
<ul>
    <li>%s %s</li>
    <li>%s %s %s</li>
</ul>
HTML;
        return sprintf($template,
            $this->_elementUseDateNo->getElementHtml(), str_replace('{X}', $this->_elementDays->getElementHtml(), $this->_elementUseDateNo->getLabelHtml()),
            $this->_elementUseDateYes->getElementHtml(), $this->_elementUseDateYes->getLabelHtml(),
            $this->_elementDate->getElementHtml());
    }

    protected function _getDaysOptions()
    {
        //TODO extract to source model or helper
        return array(
            2 => 2,
            4 => 4,
            6 => 6,
            8 => 8,
            10 => 10
        );
    }
}