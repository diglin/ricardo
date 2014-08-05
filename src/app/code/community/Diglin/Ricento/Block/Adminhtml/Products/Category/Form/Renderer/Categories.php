<?php

/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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