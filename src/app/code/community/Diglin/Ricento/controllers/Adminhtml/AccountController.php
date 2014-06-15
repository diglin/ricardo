<?php
/**
 * Diglin GmbH - Switzerland
 * 
 * User: sylvainraye
 * Date: 11.05.14
 * Time: 15:52
 *
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
}