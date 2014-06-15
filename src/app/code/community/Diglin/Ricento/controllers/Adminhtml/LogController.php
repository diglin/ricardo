<?php
/**
 * Diglin GmbH - Switzerland
 * 
 * User: sylvainraye
 * Date: 06.05.14
 * Time: 23:55
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */ 
class Diglin_Ricento_Adminhtml_LogController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Show logs of a specific product listing
     */
    public function viewAction()
    {
        $id = (int) $this->getRequest()->getParam('id');

        // @todo to implement
    }
}