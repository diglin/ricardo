<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Products Listing Model
 *
 * @method string getTitle()
 * @method string getStatus()
 * @method int    getSalesOptionsId()
 * @method int    getWebsiteId()
 * @method int    getRuleId()
 * @method string    getPublishLanguages()
 * @method string    getDefaultLanguage()
 * @method int    getLangStoreIdDe()
 * @method int    getLangStoreIdFr()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method Diglin_Ricento_Model_Products_Listing setTitle(string $title)
 * @method Diglin_Ricento_Model_Products_Listing setStatus(string $status)
 * @method Diglin_Ricento_Model_Products_Listing setSalesOptionsId(int $salesOptionsId)
 * @method Diglin_Ricento_Model_Products_Listing setWebsiteId(int $websiteId)
 * @method Diglin_Ricento_Model_Products_Listing setRuleId(int $ruleId)
 * @method Diglin_Ricento_Model_Products_Listing setPublishLanguages(string $language)
 * @method Diglin_Ricento_Model_Products_Listing setDefaultLanguage(string $language)
 * @method Diglin_Ricento_Model_Products_Listing setLangStoreIdDe(int $storeId)
 * @method Diglin_Ricento_Model_Products_Listing setLangStoreIdFr(int $storeId)
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

        if ($this->hasDataChanges() && $this->getStatus() != Diglin_Ricento_Helper_Data::STATUS_LISTED) {
            $this->setStatus(Diglin_Ricento_Helper_Data::STATUS_PENDING);

            // Be aware doing that doesn't trigger Magento events but it's faster
            $this->getProductsListingItemCollection()->updateStatusToAll(Diglin_Ricento_Helper_Data::STATUS_PENDING);

            // Delete configurable product children, will be recreated when the check list process is done
            Mage::getResourceModel('diglin_ricento/products_listing_item_collection')
                ->addFieldToFilter('products_listing_id', $this->getId())
                ->addFieldToFilter('parent_item_id', array('notnull' => 1))
                ->addFieldToFilter('ricardo_article_id', array('null' => 1))
                ->walk('delete');
        }

        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        if ($this->isObjectNew()) {
            $this->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }

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
     * @param bool $withChildren
     * @return array
     */
    public function getProductIds($withChildren = true)
    {
        if (!$this->getId()) {
            return array();
        }

        $array = $this->getData('product_ids');
        if (is_null($array)) {
            $array = $this->getResource()->getProductIds($this, $withChildren);
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
    public function removeProductsByProductIds(array $productIds)
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
     * Removes items by item id
     *
     * @param array $itemIds
     * @return int[] Returns two values: [number of removed products, number of not removed listed products]
     */
    public function removeProductsByItemIds(array $itemIds)
    {
        /** @var $items Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection */
        $items = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');

        /** @var $itemResource Diglin_Ricento_Model_Resource_Products_Listing_Item */
        $itemResource = Mage::getResourceModel('diglin_ricento/products_listing_item');
        $itemResource->beginTransaction();

        $numberOfListedItems = $items->addFieldToFilter('products_listing_id', $this->getId())
            ->addFieldToFilter('item_id', array('in' => $itemIds))
            ->addFieldToFilter('status', Diglin_Ricento_Helper_Data::STATUS_LISTED)
            ->getSize();

        $items = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
        $numberOfItemsToDelete = $items->addFieldToFilter('products_listing_id', $this->getId())
            ->addFieldToFilter('item_id', array('in' => $itemIds))
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
            $this->_salesOptions = Mage::getModel('diglin_ricento/sales_options')->load($this->getSalesOptionsId());
        }
        return $this->_salesOptions;
    }

    /**
     * @return Diglin_Ricento_Model_Rule
     */
    public function getShippingPaymentRule()
    {
        if (!$this->_shippingPaymentRule) {
            $this->_shippingPaymentRule = Mage::getModel('diglin_ricento/rule')->load($this->getRuleId());
        }
        return $this->_shippingPaymentRule;
    }
}
