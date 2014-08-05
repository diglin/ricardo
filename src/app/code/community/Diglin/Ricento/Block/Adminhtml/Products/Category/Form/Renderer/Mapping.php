<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Category_Form_Renderer_Mapping
    extends Varien_Data_Form_Element_Button
{
    /**
     * Custom button to popup Ricardo Category Window
     *
     * @return string
     */
    public function getElementHtml()
    {
        $this->addClass('button');
        $html = '';
        $html .= '<span id="'.$this->getHtmlId().'_title" class="ricardo_categories_title">' . Mage::helper('diglin_ricento')->__('No Selection') . '</span>';
        $html .= '<button type="button"' . $this->serialize($this->getHtmlAttributes())
            . ' onclick="Ricento.categoryMappingPopup(\'' . Mage::helper('adminhtml')->getUrl('ricento/products_category/mapping', array('id' => '#ID#')) . '\', $(\''. $this->getHtmlId() .'\'),  $(\''. $this->getHtmlId() .'_title\'))"'
            . ' id="'. $this->getHtmlId() .'_button">' . Mage::helper('diglin_ricento')->__('Open Ricardo Category Window') . '</button>'."\n";

        $html .= ' <input id="'.$this->getHtmlId().'" name="'.$this->getName()
            .'" type="hidden" value="'. $this->getEscapedValue() .'"/>';
        $html .= $this->getAfterElementHtml();
        return $html;
    }
}