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

        $categoryIds = $this->getRequest()->getParam('category_ids', array());

        try {
            //@todo save the products of selected categories into the products list
            $categoryIds = array_unique(explode(',', $categoryIds));
            if (!empty($categoryIds)) {
//                /* @var $productCollection Mage_Catalog_Model_Resource_Product_Collection */
//                $productCollection = Mage::getResourceModel('catalog/product_collection');
//                $productCollection->addCategoryFilter()

                /* @var $categoryCollection Mage_Catalog_Model_Resource_Category_Collection */
                //$categoryCollection = Mage::getResourceModel('catalog/category_collection');

                $layer = Mage::getSingleton('catalog/layer');

                foreach ($categoryIds as $categoryId) {
                    $layer->setCurrentCategory($categoryId);
                    $productCollection = $layer->getProductCollection();
                }
            }


            $this->_getSession()->addSuccess('Products from the selected categories have been added to your products listing.');
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($this->__('Error occured while saving the product(s) from the selected categories. Please check your exception log.'));
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