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
 * Class Diglin_Ricento_Adminhtml_AccountController
 */
class Diglin_Ricento_Adminhtml_AccountController extends Mage_Adminhtml_Controller_Action
{
    public function signupAction()
    {
        $block = $this->getLayout()->createBlock('core/template');

        $block
            ->setTemplate('ricento/iframe.phtml')
            ->setTitle($this->__('ricardo.ch API Signup'))
            ->setIframeUrl(Mage::helper('diglin_ricento')->getRicardoSignupApiUrl(false));

        $ricardoLabel = $this->__('ricardo.ch');
        $signupLabel = $this->__('API Signup');

        $this->_title($signupLabel);

        $this->loadLayout()
            ->_setActiveMenu('ricento/signup')
            ->_addBreadcrumb($ricardoLabel, $ricardoLabel)
            ->_addBreadcrumb($signupLabel, $signupLabel)
            ->_addContent($block)
            ->renderLayout();
    }

    public function assistantAction()
    {
        $block = $this->getLayout()->createBlock('core/template');

        $block
            ->setTemplate('ricento/iframe.phtml')
            ->setTitle($this->__('ricardo.ch Assistant Portal'))
            ->setIframeUrl(Mage::helper('diglin_ricento')->getRicardoAssistantUrl());

        $ricardoLabel = $this->__('ricardo.ch');
        $assistantLabel = $this->__('Assistant Portal');

        $this->_title($assistantLabel);

        $this->loadLayout()
            ->_setActiveMenu('ricento/assistant')
            ->_addBreadcrumb($ricardoLabel, $ricardoLabel)
            ->_addBreadcrumb($assistantLabel, $assistantLabel)
            ->_addContent($block)
            ->renderLayout();
    }
}