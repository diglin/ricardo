<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
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
            ->setTitle($this->__('Ricardo API Signup'))
            ->setIframeUrl(Mage::helper('diglin_ricento')->getRicardoSignupApiUrl(false));

        $ricardoLabel = $this->__('Ricardo');
        $signupLabel = $this->__('API Signup');

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
            ->setTitle($this->__('Ricardo Assistant Portal'))
            ->setIframeUrl(Mage::helper('diglin_ricento')->getRicardoAssistantUrl());

        $ricardoLabel = $this->__('Ricardo');
        $assistantLabel = $this->__('Assistant Portal');

        $this->loadLayout()
            ->_setActiveMenu('ricento/assistant')
            ->_addBreadcrumb($ricardoLabel, $ricardoLabel)
            ->_addBreadcrumb($assistantLabel, $assistantLabel)
            ->_addContent($block)
            ->renderLayout();
    }
}