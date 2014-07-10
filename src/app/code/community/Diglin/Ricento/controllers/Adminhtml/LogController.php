<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
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