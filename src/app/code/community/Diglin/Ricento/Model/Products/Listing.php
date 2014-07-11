<?php
/**
 * This file is part of Diglin_Ricento for Magento.
 *
 * @license proprietary
 * @author Fabian Schmengler <fs@integer-net.de> <fschmengler>
 * @category Diglin
 * @package Diglin_Ricento
 * @copyright Copyright (c) 2014 Diglin GmbH (http://www.diglin.com/)
 */

/**
 * Products Listing Model
 *
 * @package Diglin_Ricento
 * @method string getTitle() getTitle()
 * @method int    getTotalActiveProducts() getTotalActiveProducts()
 * @method int    getTotalInActiveProducts() getTotalInActiveProducts()
 * @method int    getTotalSoldProducts() getTotalSoldProducts()
 * @method int    getTotalProducts() getTotalProducts()
 * @method string getStatus() getStatus()
 * @method int    getSalesOptionsId() getSalesOptionsId()
 * @method int    getStoreId() getStoreId()
 * @method DateTime getCreatedAt() getCreatedAt()
 * @method DateTime getUpdatedAt() getUpdatedAt()
 * @method Diglin_Ricento_Model_Products_Listing setTitle() setTitle(string $title)
 * @method Diglin_Ricento_Model_Products_Listing setTotalActiveProducts() setTotalActiveProducts(int $totalActiveProducts)
 * @method Diglin_Ricento_Model_Products_Listing setTotalInActiveProducts() setTotalInActiveProducts(int $totalInactiveProducts)
 * @method Diglin_Ricento_Model_Products_Listing setTotalSoldProducts() setTotalSoldProducts(int $totalSoldProducts)
 * @method Diglin_Ricento_Model_Products_Listing setTotalProducts() setTotalProducts(int $totalProducts)
 * @method Diglin_Ricento_Model_Products_Listing setStatus() setStatus(string $status)
 * @method Diglin_Ricento_Model_Products_Listing setSalesOptionsId() setSalesOptionsId(int $salesOptionsId)
 * @method Diglin_Ricento_Model_Products_Listing setStoreId() setStoreId(int $storeId)
 * @method Diglin_Ricento_Model_Products_Listing setCreatedAt() setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Products_Listing setUpdatedAt() setUpdatedAt(DateTime $updatedAt)
 */
class Diglin_Ricento_Model_Products_Listing extends Mage_Core_Model_Abstract
{

// Diglin GmbH Tag NEW_CONST

// Diglin GmbH Tag NEW_VAR

    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'products_listing';

    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() in this case
     * @var string
     */
    protected $_eventObject = 'products_listing';

    /**
     * Products_Listing Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/products_listing');
    }

// Diglin GmbH Tag NEW_METHOD

    /**
     * Retrieve array of product id's for listing
     *
     * @return array
     */
    public function getProductIds()
    {
        if (!$this->getId()) {
            return array();
        }

        $array = $this->getData('product_ids');
        if (is_null($array)) {
            $array = $this->getResource()->getProductIds($this);
            $this->setData('product_ids', $array);
        }
        return $array;
    }

    /**
     * Adds new item by product id
     *
     * @param $productId
     * @return bool true if product has been added
     */
    public function addProduct($productId)
    {
        if (Mage::getResourceModel('catalog/product_collection')->addFieldToFilter('entity_id', $productId)->getSize()) {
            /** @var $productListingItem Diglin_Ricento_Model_Products_Listing_Item */
            $productListingItem = Mage::getModel('diglin_ricento/products_listing_item');
            $productListingItem->setProductsListingId($this->getId())->setProductId($productId)->save();
            return true;
        }
        return false;
    }

    /**
     * Removes items by product id
     *
     * @param array $productId
     * @return int number of removed products
     */
    public function removeProducts(array $productIds)
    {
        /** @var $items Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection */
        $items = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
        $items->addFieldToFilter('products_listing_id', $this->getId())
            ->addFieldToFilter('product_id', array('in' => $productIds))
            ->load();
        $numberOfItems = count($items);
        if ($numberOfItems) {
            $items->walk('delete');
        }
        return $numberOfItems;
    }
}