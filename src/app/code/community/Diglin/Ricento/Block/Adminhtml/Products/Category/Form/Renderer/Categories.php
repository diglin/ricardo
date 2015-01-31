<?php

/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Category_Form_Renderer_Categories
 */
class Diglin_Ricento_Block_Adminhtml_Products_Category_Form_Renderer_Categories
    extends Diglin_Ricento_Block_Adminhtml_Products_Category_Tree_Categories
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;

    public function getElement()
    {
        return $this->_element;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
           return $this->toHtml();
    }
}