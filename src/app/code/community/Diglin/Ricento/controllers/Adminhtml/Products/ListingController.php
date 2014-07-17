<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
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

    /**
     * @return bool|Diglin_Ricento_Model_Products_Listing
     */
    protected function _initListing()
    {
        $registeredListing = Mage::registry('products_listing');
        if ($registeredListing) {
            return $registeredListing;
        }
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
     * Create empty product listing based on name and store_id
     */
    public function createAction()
    {
        $listingTitle = (string) $this->getRequest()->getPost('listing_title');
        $storeId = (int) $this->getRequest()->getPost('store_id');
        if (empty($listingTitle) || empty($storeId)) {
            $this->_getSession()->addError($this->__('Listing name and store must be specified.'));
            $this->_redirect('*/*/index');
            return;
        }

        /* @var $salesOptions Diglin_Ricento_Model_Sales_Options */
        $salesOptions = Mage::getModel('diglin_ricento/sales_options');
        $salesOptions->setDataChanges(true)->save();

        /* @var $listing Diglin_Ricento_Model_Products_Listing */
        $listing = Mage::getModel('diglin_ricento/products_listing');
        $listing->setTitle($listingTitle)
            ->setStoreId($storeId)
            ->setSalesOptionsId($salesOptions->getId())
            ->save();
        $this->_redirect('*/*/edit', array('id' => $listing->getId()));
    }
    /**
     * Save a product listing
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $data = $this->_filterPostData($data);
            $listing = $this->_initListing();
            if (!$listing) {
                $this->_redirect('*/*/index');
                return;
            }
            if ($listing->getStatus() === Diglin_Ricento_Helper_Data::STATUS_LISTED) {
                $this->_getSession()->addError($this->__('Listed listings cannot be modified. Stop the listing first to make changes.'));
                $this->_redirect('*/*/edit', array('id' => $listing->getId(), '_current' => true));
                return;
            }

//            echo '<pre>';
//            var_dump($this->getRequest()->getPost());
//            exit;

            $listing->setData($data['product_listing']);
            $listing->getSalesOptions()->setData($data['sales_options']);

            if (!$this->_validatePostData($data)) {
                $this->_redirect('*/*/edit', array('id' => $listing->getId(), '_current' => true));
                return;
            }

            try {
                $listing->save();
                $listing->getSalesOptions()->save();

                $this->_getSession()->addSuccess($this->__('The listing has been saved.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $listing->getId(), '_current'=>true));
                    return;
                }
                $this->_redirect('*/*/index');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addException($e, $this->__('An error occurred while saving the listing.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
        }
        $this->_redirect('*/*/');
    }

    /**
     * Save a product listing and set status to "listed"
     */
    public function saveAndListAction()
    {
        $this->saveAction();
        $this->listAction();
    }

    /**
     * Delete a product listing
     */
    public function deleteAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        if ($this->_getListing()->getStatus() === Diglin_Ricento_Helper_Data::STATUS_LISTED) {
            $this->_getSession()->addError($this->__('Listed listings cannot be deleted. Stop the listing first.'));
            $this->_redirect('*/*/index');
            return;
        }
        $this->_getListing()->delete();
        $this->_getSession()->addSuccess($this->__('Listing deleted'));
        $this->_redirect('*/*/index');
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
     * Remove product(s) from product listing
     */
    public function removeProductAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        if ($this->getRequest()->isPost()) {
            $productIds = array_map('intval', (array) $this->getRequest()->getPost('product', array()));
        } else {
            $productIds = array_map('intval', (array) $this->getRequest()->getParam('product', array()));
        }
        $productsRemoved = $this->_getListing()->removeProducts($productIds);
        $this->_getSession()->addSuccess($this->__('%d products removed from listing', $productsRemoved));
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
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        if ($this->_getListing()->getStatus() === Diglin_Ricento_Helper_Data::STATUS_LISTED) {
            $this->_getSession()->addError($this->__('Listing is already listed. To list new items again, use "Relist"'));
            $this->_redirect('*/*/index');
            return;
        }
        $this->_getListing()->setStatus(Diglin_Ricento_Helper_Data::STATUS_LISTED)->save();
        //TODO set status for items as well if necessary
        $this->_getSession()->addSuccess($this->__('The listing has been listed.'));
        $this->_redirect('*/*/index');
    }

    /**
     * Start to list the product listing on ricardo platform if was already listed but
     * some products need to be again listed
     */
    public function relistAction()
    {
        if ($this->_getListing()->getStatus() !== Diglin_Ricento_Helper_Data::STATUS_LISTED) {
            $this->_getSession()->addError($this->__('Listing is not listed. To list a new or stopped listing, use "List"'));
            $this->_redirect('*/*/index');
            return;
        }
        //TODO relist items
        $this->_getSession()->addSuccess($this->__('Products relisted.'));
        $this->_redirect('*/*/index');
    }

    /**
     * Stop to list all items belonging to a product list from ricardo platform
     */
    public function stopAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/*/index');
            return;
        }
        if ($this->_getListing()->getStatus() !== Diglin_Ricento_Helper_Data::STATUS_LISTED) {
            $this->_getSession()->addError($this->__('Only listed listings can be stopped.'));
            $this->_redirect('*/*/index');
            return;
        }
        $this->_getListing()->setStatus(Diglin_Ricento_Helper_Data::STATUS_STOPPED)->save();
        //TODO set status for items as well if necessary
        $this->_getSession()->addSuccess($this->__('Listing stopped.'));
        $this->_redirect('*/*/index');
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

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        if (!isset($data['product_listing'])) {
            $data['product_listing'] = array();
        }

        if (!isset($data['sales_options'])) {
            $data['sales_options'] = array();
        }
        $data['sales_options'] = $this->_filterDates($data['sales_options'], array('schedule_date_start', 'schedule_period_end_date'));
        if (!empty($data['sales_options']['schedule_cycle_multiple_products_random'])) {
            $data['sales_options']['schedule_cycle_multiple_products'] = null;
        }
        if (!empty($data['sales_options']['stock_management_use_inventory'])) {
            $data['sales_options']['stock_management'] = -1;
        }
        if (!empty($data['sales_options']['schedule_date_start_immediately'])) {
            $data['sales_options']['schedule_date_start'] = date(Varien_Date::DATE_PHP_FORMAT);
        }
        if (!empty($data['sales_options']['schedule_period_use_end_date'])) {
            $data['sales_options']['schedule_period_days'] = date_diff(
                new DateTime($data['sales_options']['schedule_date_start']),
                new DateTime($data['sales_options']['schedule_period_end_date']))->days;
        }
        return $data;
    }

    /**
     * Validate post data
     *
     * @param array $data
     * @return bool     Return FALSE if some item is invalid
     */
    protected function _validatePostData($data)
    {
        //TODO validation if necessary
        return true;
    }
}