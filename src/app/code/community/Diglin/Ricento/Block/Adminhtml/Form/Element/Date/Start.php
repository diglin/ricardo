<?php
class Diglin_Ricento_Block_Adminhtml_Form_Element_Date_Start extends Varien_Data_Form_Element_Abstract
{
    /**
     * @var Varien_Data_Form_Element_Radio
     */
    protected $_elementImmediatelyYes;
    /**
     * @var Varien_Data_Form_Element_Radio
     */
    protected $_elementImmediatelyNo;
    /**
     * @var Varien_Data_Form_Element_Date
     */
    protected $_elementDate;

    protected function _addChildFields()
    {
        $radioName = $this->getName() . '_immediately';
        $this->_elementImmediatelyYes = $this->addField($radioName . '_yes', 'radio', array(
            'name'  => $radioName,
            'value' => 1,
            'label' => Mage::helper('diglin_ricento')->__('Start immediately')
        ));
        $this->_elementImmediatelyNo = $this->addField($radioName . '_no', 'radio', array(
            'name'  => $radioName,
            'value' => 0,
            'label' => Mage::helper('diglin_ricento')->__('Start from')
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
            $this->_elementImmediatelyYes->getElementHtml(), $this->_elementImmediatelyYes->getLabelHtml(),
            $this->_elementImmediatelyNo->getElementHtml(), $this->_elementImmediatelyNo->getLabelHtml(),
            $this->_elementDate->getElementHtml());
    }

}