<?php
/**
 * Diglin GmbH - Switzerland
 * 
 * User: sylvainraye
 * Date: 16.05.14
 * Time: 00:02
 *
 * @category    Diglin Magento Demo
 * @package     Diglin_
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */ 
class Diglin_Ricento_Adminhtml_Products_CategoryController extends Mage_Adminhtml_Controller_Action
{

    public function mappingAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Save the mapping done of the categories between Magento & Ricardo
     */
    public function saveAction()
    {

    }
}