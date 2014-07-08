<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
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