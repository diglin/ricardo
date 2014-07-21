<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Catalog_Category_Form_Renderer_Mapping extends Varien_Data_Form_Element_Button
{
    /**
     * Custom button to popup Ricardo Category Window
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = '<input value="' . Mage::helper('diglin_ricento')->__('Open Ricardo Category Window') . '" ' . $this->serialize($this->getHtmlAttributes())
            . ' onclick="Ricento.categoryMappingPopup(\'' . Mage::helper('adminhtml')->getUrl('ricento/products_category/mapping') . '\', $(\''. $this->getHtmlId() .'\'))"'
            . '/>'."\n";

        // Value to be filled via a javascript script when clicked on a linked in the popup window of the Ricardo Category
        // @todo finish the implementation here - add javascript control (Sylvain has a script for such situation if needed let me know)
        $html .= ' <input id="'.$this->getHtmlId().'" name="'.$this->getName()
            .'" type="hidden" value="'. $this->getEscapedValue() .'"/>';
        $html .= $this->getAfterElementHtml();
        return $html;
    }
}