<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Adminhtml_Products_CategoryController
 */
class Diglin_Ricento_Adminhtml_Products_CategoryController extends Diglin_Ricento_Controller_Adminhtml_Action
{
    public function mappingAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('category_mapping')
            ->setCategoryId($this->getRequest()->getParam('id', 1));
        $this->renderLayout();
    }

    public function childrenAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('category_children')
            ->setCategoryId($this->getRequest()->getParam('id', 1))
            ->setLevel($this->getRequest()->getParam('level', 0));
        $this->renderLayout();
    }

    /**
     * Show the categories tree to add products related to them into a product listing item
     */
    public function showCategoriesTreeAction()
    {
        $noContentRender = false;

        if (!$this->_initListing()) {
            $this->_getSession()->addError($this->__('No category will be displayed, the products listing doesn\'t exists. Please, close the window.'));
            $noContentRender = true;
        }

        if (!$this->_savingAllowed()) {
            $this->_getSession()->addError($this->__('You are not allowed to save the products listing, so you cannot add products from a category. Please, close the window.'));
            $noContentRender = true;
        }

        $this->loadLayout();

        if ($noContentRender) {
            $this->getLayout()->getBlock('content')->unsetChild('products_listing_categories');
        }

        $this->renderLayout();
    }

    /**
     * Use for ajax call to load new categories in the tree
     */
    public function categoriesJsonAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('diglin_ricento/adminhtml_products_category_tree_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }

    public function saveProductsToAddAction()
    {
        if (!$this->_initListing()) {
            $this->_getSession()->addError($this->__('Product(s) from the selected categories cannot be saved. The products listing doesn\'t exists.'));
            $this->_redirect('*/products_listing/index');
            return;
        }

        if (!$this->_savingAllowed()) {
            $this->_getSession()->addError($this->__('You are not allowed to save the products listing, so you cannot add products from a category.'));
            $this->_redirect('*/products_listing/index');
            return;
        }

        $categoryIds = $this->getRequest()->getParam('category_ids', array());

        try {
            $productsAdded = 0;
            $categoryIds = array_unique(explode(',', $categoryIds));
            if (!empty($categoryIds)) {
                $supportedTypes = Mage::helper('diglin_ricento')->getAllowedProductTypes();

                $productsListingItemIds = (array) $this->_getListing()
                    ->getProductsListingItemCollection()
                    ->getColumnValues('product_id');

                $categories = Mage::getResourceModel('catalog/category_collection')
                    ->addFieldToFilter('entity_id', array('in' => $categoryIds));

                foreach ($categories->getItems() as $category) {
                    /* Only supported products type, not already in the current & other list */
                    $productCollection = Mage::getResourceModel('catalog/product_collection')
                        ->addWebsiteFilter($this->_getListing()->getWebsiteId())
                        ->addFieldToFilter('type_id', array('in' => $supportedTypes))
                        ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                        ->joinField('in_other_list',
                            'diglin_ricento/products_listing_item',
                            new Zend_Db_Expr('products_listing_id IS NOT NULL'),
                            'product_id=entity_id',
                            'products_listing_id !=' . (int) $this->_getListing()->getId(),
                            'left'
                        )
                        ->addFieldToFilter('in_other_list', array('eq' => 0))
                        ->groupByAttribute('entity_id')
                        ->addCategoryFilter($category);

                    if (!empty($productsListingItemIds)) {
                        $productCollection->addFieldToFilter('entity_id', array('nin' => $productsListingItemIds));
                    }

                    $productIds = $productCollection->getAllIds();
                    $productsListingItemIds = array_merge($productsListingItemIds, $productIds);
                    foreach ($productIds as $productId) {
                        if ($this->_getListing()->addProduct((int) $productId)) {
                            ++$productsAdded;
                        }
                    }
                }
            }

            $this->_getSession()->addSuccess($this->__('%d product(s) added to the listing', $productsAdded));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($this->__('Error occurred while saving the product(s) from the selected categories. Please check your exception log.'));
        }
        $this->_redirect('*/products_listing/edit', array('id' => $this->_getListing()->getId()));
    }

    /**
     * @return boolean
     */
    protected function _savingAllowed()
    {
        return $this->_getListing()->getStatus() !== Diglin_Ricento_Helper_Data::STATUS_LISTED;
    }
}