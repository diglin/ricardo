<?php
/**
 * Diglin GmbH - Switzerland
 * 
 * User: sylvainraye
 * Date: 07.05.14
 * Time: 00:00
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */ 
class Diglin_Ricento_Adminhtml_Products_ListingController extends Mage_Adminhtml_Controller_Action
{

    protected function _construct()
    {
        // important to get appropriate translation from this module
        $this->setUsedModuleName('Diglin_Ricento');
    }
    protected function _initListing()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if (!$id) {
            $this->_getSession()->addError('Product Listing not found.');
            return false;
        }

        $productsListing = Mage::getModel('diglin_ricento/products_listing')->load($id);
        Mage::register('products_listing', $productsListing);

        return $productsListing;
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    protected function _getListing()
    {
        return Mage::registry('products_listing');
    }

    /**
     * Show the products listing
     */
    public function indexAction()
    {
        $title = $this->__('Products Listing');

        $this->loadLayout()
            ->_setActiveMenu('ricento/products_listing')
            ->_addBreadcrumb($title, $title);

        $this->_title($this->__('Products Listing'));
        $this->_addContent($this->getLayout()->createBlock('diglin_ricento/adminhtml_products_listing', 'products_listing'));
        $this->renderLayout();
    }
    public function productsGridAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('diglin_ricento/adminhtml_products_listing_edit_tabs_products')->toHtml()
        );
    }
    public function addProductsPopupAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }
    public function addProductsGridAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('diglin_ricento/adminhtml_products_listing_edit_tabs_products_add')->toHtml()
        );
    }
    /**
     * Edit a product listing item
     */
    public function editAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Save a product listing item
     */
    public function saveAction()
    {

    }

    /**
     * Delete a product listing item
     */
    public function deleteAction()
    {

    }

    /**
     * Add product(s) into a product listing item
     */
    public function addProductAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        $productIds = (array) $this->getRequest()->getPost('product', array());
        $productsAdded = 0;
        foreach ($productIds as $productId) {
            if ($this->_getListing()->addProduct((int) $productId)) {
                ++$productsAdded;
            }
        }
        $this->_getSession()->addSuccess($this->__('%d products added to listing', $productsAdded));
        $this->_redirect('*/*/edit', array('id' => $this->_getListing()->getId()));
    }

    /**
     * Add product(s) from a selected category into a product listing item
     */
    public function addProductFromCategoryAction()
    {

    }

    /**
     * Start to list the product listing on ricardo platform if not already listed
     */
    public function listAction()
    {

    }

    /**
     * Start to list the product listing on ricardo platform if was already listed but
     * some products need to be again listed
     */
    public function relistAction()
    {

    }

    /**
     * Stop to list all items belonging to a product list from ricardo platform
     */
    public function stopAction()
    {

    }

    /**
     * Delete one or more product listing via mass action
     * Maybe redirected to the method deleteAction with variable changes
     */
    public function massDeleteAction()
    {

    }

    /**
     * Change the status of the product listing via mass action
     * Depending on the status, it may be redirected to an other method of this controller
     */
    public function massStatusAction()
    {

    }
    /**
     * View logs of selected product listings
     */
    public function massViewLogsAction()
    {

    }
}