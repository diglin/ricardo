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
class Diglin_Ricento_Block_Adminhtml_Products_Category_Tree extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_controller = 'adminhtml_products_category';
    protected $_mode = 'tree';
    protected $_blockGroup = 'diglin_ricento';

    public function __construct()
    {
        parent::__construct();

        $this->removeButton('back');
        $this->removeButton('reset');
        $this->removeButton('delete');
        $this->_headerText = $this->__('Add products from selected categories');

        Mage::getSingleton('adminhtml/session')->addNotice($this->getInstructionsText());
    }

    public function getInstructionsText()
    {
        $instructions = $this->__('Only product types supported, enabled and not listed in other products listing will be added.');
        $instructions .= '&nbsp;';
        $instructions .= $this->__('Be aware, you have also to select the sub-categories, otherwise selecting only a top category won\'t allow to add products of its subcategories.');
        return $instructions;
    }
}