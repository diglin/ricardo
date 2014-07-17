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
 * Products_Listing_Item Model
 * @package Diglin_Ricento
 * @method int    getProductId() getProductId()
 * @method int    getProductsListingId() getProductsListingId()
 * @method int    getSalesOptionsId() getSalesOptionsId()
 * @method string getStatus() getStatus()
 * @method DateTime getCreatedAt() getCreatedAt()
 * @method DateTime getUpdatedAt() getUpdatedAt()
 * @method Diglin_Ricento_Model_Products_Listing_Item setProductId() setProductId(int $productId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setProductsListingId() setProductsListingId(int $productListingId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setSalesOptionsId() setSalesOptionsId(int $salesOptionsId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setStatus() setStatus(string $status)
 * @method Diglin_Ricento_Model_Products_Listing_Item setCreatedAt() setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Products_Listing_Item setUpdatedAt() setUpdatedAt(DateTime $updatedAt)
 */
class Diglin_Ricento_Model_Products_Listing_Item extends Mage_Core_Model_Abstract
{

    /**
     * @var Diglin_Ricento_Model_Sales_Options
     */
    protected $_salesOptions;
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

// Diglin GmbH Tag NEW_METHOD

    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::getModel('catalog/product')->load($this->getProductId());
        }
        return $this->_product;
    }
    public function getSalesOptions()
    {
        if (!$this->_salesOptions) {
            $this->_salesOptions = Mage::getModel('diglin_ricento/sales_options');
            if ($this->getSalesOptionsId()) {
                $this->_salesOptions->load($this->getSalesOptionsId());
            }
        }
        return $this->_salesOptions;
    }
}