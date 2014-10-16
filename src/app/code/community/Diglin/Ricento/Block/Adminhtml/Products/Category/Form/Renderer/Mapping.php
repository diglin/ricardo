<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
        $value = $this->getEscapedValue();
        if (!empty($value) && $value != -1) {
            $categoryName = Mage::getSingleton('diglin_ricento/products_category_mapping')->getCategory($value)->getCategoryName();
            $text = $categoryName;
        } else {
            $text = Mage::helper('diglin_ricento')->__('No Selection');
        }

        $this->addClass('button');
        $html = '<span id="'.$this->getHtmlId().'_title" class="ricardo_categories_title">' . $text . '</span>';
        $html .= '<button type="button"' . $this->serialize($this->getHtmlAttributes())
            . ' onclick="Ricento.categoryMappingPopup(\''
            . Mage::helper('adminhtml')->getUrl('ricento/products_category/mapping', array('id' => '#ID#'))
            . '\', $(\''. $this->getHtmlId() .'\'),  $(\''. $this->getHtmlId() .'_title\'))"'
            . ' id="'. $this->getHtmlId() .'_button">'
            . Mage::helper('diglin_ricento')->__('Open Ricardo Category Window')
            . '</button>'."\n";

        $html .= ' <input id="'.$this->getHtmlId().'" name="'.$this->getName()
            .'" type="hidden" value="'. $this->getEscapedValue() .'" />';
        $html .= $this->getAfterElementHtml();
        return $html;
    }
}