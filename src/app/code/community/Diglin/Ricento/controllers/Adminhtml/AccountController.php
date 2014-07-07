<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Adminhtml_AccountController extends Mage_Adminhtml_Controller_Action
{
    public function signupAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function assistantAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}