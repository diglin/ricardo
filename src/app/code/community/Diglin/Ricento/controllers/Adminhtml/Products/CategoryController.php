<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Adminhtml_Products_CategoryController
 */
class Diglin_Ricento_Adminhtml_Products_CategoryController extends Mage_Adminhtml_Controller_Action
{
   //TODO verhalten bei session expire
    public function mappingAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('category_mapping')
            ->setCategoryId($this->getRequest()->getParam('id', 1));
        $this->renderLayout();
    }

    public function childrenAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('category_children')
            ->setCategoryId($this->getRequest()->getParam('id', 1))
            ->setLevel($this->getRequest()->getParam('level', 0));
        $this->renderLayout();
    }
    /**
     * Save the mapping done of the categories between Magento & Ricardo
     */
    public function saveAction()
    {

    }
}