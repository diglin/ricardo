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
 * Class Diglin_Ricento_Adminhtml_DashboardController
 */
class Diglin_Ricento_Adminhtml_DashboardController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('ricento/dashboard');
    }

    /**
     * Ricento dashboard
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Proxy for Google Chart API, reused from core dashboard
     */
    public function tunnelAction()
    {
        $this->_forward('tunnel', 'dashboard', 'admin');
    }
}