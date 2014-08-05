<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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
    }
}