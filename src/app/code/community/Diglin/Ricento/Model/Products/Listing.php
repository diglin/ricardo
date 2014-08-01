<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Products Listing Model
 *
 * @method string getTitle()
 * @method int    getTotalActiveProducts()
 * @method int    getTotalInActiveProducts()
 * @method int    getTotalSoldProducts()
 * @method int    getTotalProducts()
 * @method string getStatus()
 * @method int    getSalesOptionsId()
 * @method int    getStoreId()
 * @method int    getRuleId()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method Diglin_Ricento_Model_Products_Listing setTitle(string $title)
 * @method Diglin_Ricento_Model_Products_Listing setTotalActiveProducts(int $totalActiveProducts)
 * @method Diglin_Ricento_Model_Products_Listing setTotalInActiveProducts(int $totalInactiveProducts)
 * @method Diglin_Ricento_Model_Products_Listing setTotalSoldProducts(int $totalSoldProducts)
 * @method Diglin_Ricento_Model_Products_Listing setTotalProducts(int $totalProducts)
 * @method Diglin_Ricento_Model_Products_Listing setStatus(string $status)
 * @method Diglin_Ricento_Model_Products_Listing setSalesOptionsId(int $salesOptionsId)
 * @method Diglin_Ricento_Model_Products_Listing setStoreId(int $storeId)
 * @method Diglin_Ricento_Model_Products_Listing setRuleId(int $ruleId)
 * @method Diglin_Ricento_Model_Products_Listing setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Products_Listing setUpdatedAt(DateTime $updatedAt)
 */
class Diglin_Ricento_Model_Products_Listing extends Mage_Core_Model_Abstract
{


    /**
     * @var Diglin_Ricento_Model_Sales_Options
     */
    protected $_salesOptions;

    /**
     * @var Diglin_Ricento_Model_Rule
     */
    protected $_shippingPaymentRule;

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

    /**
     * Set date of last update
     *
     * @return Diglin_Ricento_Model_Products_Listing
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }

    /**
     * @return $this|Mage_Core_Model_Abstract
     */
    protected function _beforeDelete()
    {
        parent::_beforeDelete();

        // We must not use the FK constrains cause of deletion of other values at item level
        $this->getProductsListingItemCollection()->walk('delete');
        return $this;
    }

    /**
     * @return $this|Mage_Core_Model_Abstract
     */
    protected function _afterDeleteCommit()
    {
        $this->getSalesOptions()->delete();
        $this->getShippingPaymentRule()->delete();

        parent::_afterDeleteCommit();
        return $this;
    }

    /**
     * @return Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection
     */
    public function getProductsListingItemCollection()
    {
        return Mage::getResourceModel('diglin_ricento/products_listing_item_collection')
            ->addFieldToFilter('products_listing_id', array('eq' => $this->getId()));
    }

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
     * @param array $productIds
     * @return int[] Returns two values: [number of removed products, number of not removed listed products]
     */
    public function removeProducts(array $productIds)
    {
        /** @var $items Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection */
        $items = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');

        /** @var $itemResource Diglin_Ricento_Model_Resource_Products_Listing_Item */
        $itemResource = Mage::getResourceModel('diglin_ricento/products_listing_item');
        $itemResource->beginTransaction();

        $numberOfListedItems = $items->addFieldToFilter('products_listing_id', $this->getId())
            ->addFieldToFilter('product_id', array('in' => $productIds))
            ->addFieldToFilter('status', Diglin_Ricento_Helper_Data::STATUS_LISTED)
            ->getSize();

        $items = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
        $numberOfItemsToDelete = $items->addFieldToFilter('products_listing_id', $this->getId())
            ->addFieldToFilter('product_id', array('in' => $productIds))
            ->addFieldToFilter('status', array('neq' => Diglin_Ricento_Helper_Data::STATUS_LISTED))
            ->count();
        if ($numberOfItemsToDelete) {
            $items->walk('delete');
        }

        $itemResource->commit();
        return array($numberOfItemsToDelete, $numberOfListedItems);
    }

    /**
     * @return Diglin_Ricento_Model_Sales_Options
     */
    public function getSalesOptions()
    {
        if (!$this->_salesOptions) {
            $this->_salesOptions = Mage::getModel('diglin_ricento/sales_options');
            $this->_salesOptions->load($this->getSalesOptionsId());
        }
        return $this->_salesOptions;
    }

    /**
     * @return Diglin_Ricento_Model_Rule
     */
    public function getShippingPaymentRule()
    {
        if (!$this->_shippingPaymentRule) {
            $this->_shippingPaymentRule = Mage::getModel('diglin_ricento/rule');
            $this->_shippingPaymentRule->load($this->getRuleId());
        }
        return $this->_shippingPaymentRule;
    }
}