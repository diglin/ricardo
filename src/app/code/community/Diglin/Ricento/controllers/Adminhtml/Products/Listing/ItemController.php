<?php
class Diglin_Ricento_Adminhtml_Products_Listing_ItemController extends Mage_Adminhtml_Controller_Action
{
    //TODO DRY
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

        if ($this->getRequest()->isPost()) {
            $productIds = array_map('intval', (array) $this->getRequest()->getPost('product', array()));
        } else {
            $productIds = array_map('intval', (array) $this->getRequest()->getParam('product', array()));
        }
        /* @var $itemCollection Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection */
        $itemCollection = Mage::getModel('diglin_ricento/products_listing_item')->getCollection();
        $itemCollection->addFieldToFilter('products_listing_id', $productsListing->getId())
                       ->addFieldToFilter('product_id', array('in' => $productIds));
        Mage::register('selected_items', $itemCollection->load());

        return $productsListing;
    }

    public function configureAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/products_listing/index');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }
    public function configurePopupAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function saveAction()
    {
        //TODO implement
    }
}