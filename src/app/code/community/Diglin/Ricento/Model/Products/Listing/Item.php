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
 * Products_Listing_Item Model
 *
 * @method int    getProductId()
 * @method int    getProductsListingId()
 * @method int    getSalesOptionsId()
 * @method int    getRuleId()
 * @method string getStatus()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method bool getLoadFallbackOptions()
 * @method Diglin_Ricento_Model_Products_Listing_Item setProductId(int $productId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setProductsListingId(int $productListingId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setSalesOptionsId(int $salesOptionsId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setRuleId(int $ruleIid)
 * @method Diglin_Ricento_Model_Products_Listing_Item setStatus(string $status)
 * @method Diglin_Ricento_Model_Products_Listing_Item setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Products_Listing_Item setUpdatedAt(DateTime $updatedAt)
 * @method Diglin_Ricento_Model_Products_Listing_Item setLoadFallbackOptions(bool $loadFallbackOptions)
 */
class Diglin_Ricento_Model_Products_Listing_Item extends Mage_Core_Model_Abstract
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
    protected $_eventPrefix = 'products_listing_item';

    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() in this case
     * @var string
     */
    protected $_eventObject = 'products_listing_item';

    /**
     * Associated product
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_product;

    /**
     * Products_Listing_Item Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/products_listing_item');
    }

    /**
     * Set date of last update
     *
     * @return Diglin_Ricento_Model_Products_Listing_Item
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        if ($this->hasDataChanges() && $this->getStatus() == Diglin_Ricento_Helper_Data::STATUS_READY) {
            $this->setStatus(Diglin_Ricento_Helper_Data::STATUS_PENDING);
        }

        return $this;
    }

    protected function _afterDeleteCommit()
    {
        if ($this->getSalesOptionsId()) {
            $this->getSalesOptions()->delete();
        }

        if ($this->getRuleId()) {
            $this->getShippingPaymentRule()->delete();
        }

        parent::_afterDeleteCommit();
        return $this;
    }

    /**
     * @return Mage_Catalog_Model_Product|Mage_Core_Model_Abstract
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::getModel('diglin_ricento/products_listing_item_product')
                ->setProductId($this->getProductId())
                ->getProduct();
        }
        return $this->_product;
    }

    /**
     * @return Diglin_Ricento_Model_Sales_Options
     */
    public function getSalesOptions()
    {
        if (!$this->_salesOptions) {
            $this->_salesOptions = Mage::getModel('diglin_ricento/sales_options');
            if ($this->getSalesOptionsId()) {
                $this->_salesOptions->load($this->getSalesOptionsId());
            } elseif ($this->getLoadFallbackOptions()) {
                $this->_salesOptions = $this->getProductsListing()->getSalesOptions();
            }
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
            if ($this->getRuleId()) {
                $this->_shippingPaymentRule->load($this->getRuleId());
            } elseif ($this->getLoadFallbackOptions()) {
                $this->_shippingPaymentRule = $this->getProductsListing()->getShippingPaymentRule();
            }
        }
        return $this->_shippingPaymentRule;
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    public function getProductsListing()
    {
        return Mage::getModel('diglin_ricento/products_listing')->load($this->getProductsListingId());
    }

    /**
     * @return int
     */
    public function getCategory()
    {
        $ricardoCategoryId = $this->getSalesOptions()->getRicardoCategory();
        if ($ricardoCategoryId < 0) {
            $catIds = $this->getProduct()->getCategoryIds();
            foreach ($catIds as $id) {
                $category = Mage::getModel('catalog/category')->load($id);
                $ricardoCategoryId = $category->getRicardoCategory();
                if ($ricardoCategoryId) {
                    break;
                }
            }
        }

        return (int) $ricardoCategoryId;
    }
}