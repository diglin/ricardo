<?php
class Diglin_Ricento_Adminhtml_Products_Listing_ItemController extends Diglin_Ricento_Controller_Adminhtml_Products_Listing
{
    /**
     * @return Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection
     */
    protected function _initItems()
    {
        if ($this->getRequest()->isPost()) {
            $productIds = array_map('intval', (array) $this->getRequest()->getPost('product', array()));
        } else {
            $productIds = array_map('intval', (array) $this->getRequest()->getParam('product', array()));
        }
        /* @var $itemCollection Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection */
        $itemCollection = Mage::getModel('diglin_ricento/products_listing_item')->getCollection();
        $itemCollection->addFieldToFilter('products_listing_id', $this->_getListing()->getId())
                       ->addFieldToFilter('product_id', array('in' => $productIds));
        Mage::register('selected_items', $itemCollection->load());

        return $itemCollection;
    }

    public function configureAction()
    {
        if (!$this->_initListing()) {
            $this->_redirect('*/products_listing/index');
            return;
        }
        if ($this->_initItems()->count() == 0) {
            $this->_getSession()->addError($this->__('No products selected.'));
            $this->_redirect('*/products_listing/edit', array('id' => $this->_getListing()->getId()));
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