<?php

/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Preview extends Mage_Adminhtml_Block_Template
{
    /**
     * @var string
     */
    protected $_template = 'ricento/products/listing/item/preview.phtml';

    /**
     * @var Diglin_Ricento_Model_Products_Listing_Item
     */
    protected $_productItem;

    /**
     * @var string
     */
    protected $_categoriesPath = array();

    /**
     * Returns items that are selected to be configured
     *
     * @return Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection
     */
    public function getSelectedItems()
    {
        return Mage::registry('selected_items');
    }

    /**
     * Returns product listing
     *
     * @return Diglin_Ricento_Model_Products_Listing
     */
    public function getListing()
    {
        return Mage::registry('products_listing');
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing_Item
     */
    public function getProductItem()
    {
        if (empty($this->_productItem)) {

            // Little bit shitty to have a collection here for one product but well, it's a feature
            // not used often + the controller is built in this way
            $this->_productItem = $this->getSelectedItems()->getFirstItem();
            $this->_productItem->setLoadFallbackOptions(true);
        }

        return $this->_productItem;
    }

    /**
     * @return Mage_Catalog_Model_Product|Mage_Core_Model_Abstract
     */
    public function getProduct()
    {
        return $this->getProductItem()->getProduct();
    }

    /**
     * @return Diglin_Ricento_Model_Sales_Options
     */
    public function getSalesOptions()
    {
        return $this->getProductItem()->getSalesOptions();
    }

    /**
     * @return Diglin_Ricento_Model_Rule
     */
    public function getShippingPaymentRule()
    {
        return $this->getProductItem()->getShippingPaymentRule();
    }

    /**
     * Get Category breadcrumb
     *
     * @return array
     */
    public function getBreadcrumb()
    {
        $categoryItem = $this->getProductItem()->getSalesOptions()->getRicardoCategory();
        $this->_getCategoriesPath($categoryItem);

        return array_reverse($this->_categoriesPath);
    }

    protected function _getCategoriesPath($category)
    {
        $categoryObject = Mage::getSingleton('diglin_ricento/products_category_mapping')
            ->getCategory($category);

        if ($categoryObject->getCategoryName()) {
            $this->_categoriesPath[] = '<a href="#">' . $categoryObject->getCategoryName() . '</a>';
        }

        $parentId = $categoryObject->getParentId();
        if (!empty($parentId)) {
            $categoryObject = $this->_getCategoriesPath($parentId);
        }

        return $categoryObject;
    }

    /**
     * Get the starting date of the auction
     *
     * @return string
     */
    public function getStartingDate()
    {
        return $this->_getLocalizedDate($this->getSalesOptions()->getScheduleDateStart());
    }

    /**
     * Get ending date sales
     *
     * @return mixed
     */
    public function getEndingDate()
    {
        $date = date_add(
            new DateTime($this->getSalesOptions()->getScheduleDateStart()),
            DateInterval::createFromDateString($this->getSalesOptions()->getSchedulePeriodDays() . ' day')
        )->format(Varien_Date::DATETIME_PHP_FORMAT);

        return $this->_getLocalizedDate($date);
    }

    /**
     * @param string $date
     * @return string
     */
    protected function _getLocalizedDate($date)
    {
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );

        $date = new Zend_Date($date, $dateFormatIso, Mage::app()->getLocale()->getLocale());
        return $date->toString();
    }

    /**
     * Get Shipping Availability
     *
     * @return mixed
     */
    public function getShippingAvailability()
    {
        $availabilities = Mage::getSingleton('diglin_ricento/config_source_rules_shipping_availability')->toOptionHash();

        foreach ($availabilities as $availabilityId => $availabilityText) {
            if ($availabilityId === (int) $this->getShippingPaymentRule()->getShippingAvailaibility()) {
                return $availabilityText;
            }
        }
        return '';
    }

    /**
     * Get Product Condition
     *
     * @return mixed
     */
    public function getProductCondition()
    {
        $conditions = Mage::getSingleton('diglin_ricento/config_source_sales_product_condition')->toOptionHash();

        foreach ($conditions as $conditionId => $conditionText) {
            if ($conditionId === (int) $this->getSalesOptions()->getProductCondition()) {
                return $conditionText;
            }
        }
        return '';
    }

    /**
     * Get payment methods of a product
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $result = array();
        $paymentMethods = Mage::getSingleton('diglin_ricento/config_source_rules_payment')->toOptionHash();
        $currentPaymentMethods = $this->getShippingPaymentRule()->getPaymentMethods();

        $this->setOtherPaymentMethod(false);
        foreach ($paymentMethods as $methodId => $methodText) {
            if (in_array($methodId, $currentPaymentMethods)) {
                $result[$methodId] = $methodText;
                if ($methodId === 0) {
                    $this->setOtherPaymentMethod(true);
                    $this->setOtherPaymentMethodText($this->escapeHtml($this->getShippingPaymentRule()->getPaymentDescription()));
                }
            }
        }
        return $result;
    }

    /**
     * Get Shipping Method Text of a product
     *
     * @return string
     */
    public function getShippingMethod()
    {
        $shippingMethods = Mage::getSingleton('diglin_ricento/config_source_rules_shipping')->toOptionHash();

        $currentShippingMethod = (array) $this->getShippingPaymentRule()->getShippingMethod();

        foreach ($shippingMethods as $methodId => $methodText) {
            if (in_array($methodId, $currentShippingMethod)) {
                if (!$methodId) {
                    $this->setOtherShippingMethodText($this->escapeHtml($this->getShippingPaymentRule()->getShippingDescription()));
                }
                return $this->escapeHtml($methodText);
            }
        }
        return '';
    }

    /**
     * Get the Shipping price in the allowed currency
     *
     * @return string
     */
    public function getShippingPrice()
    {
        return $this->_getPrice($this->getShippingPaymentRule()->getShippingPrice());
    }

    /**
     * Get the price allowed from ricardo.ch
     *
     * @param float|int $price
     * @return string
     */
    protected function _getPrice($price)
    {
        return Mage::helper('diglin_ricento')->formatPrice($price);
    }

    /**
     * Get warranty condition
     *
     * @return string
     */
    public function getWarranty()
    {
        $warrantyText = '';

        $warranties = Mage::getSingleton('diglin_ricento/config_source_sales_warranty')->toOptionHash();
        $currentWarranty = (int) $this->getSalesOptions()->getProductWarranty();

        foreach ($warranties as $warranty => $warrantyText) {
            if ($currentWarranty == $warranty) {
                if ($warranty == \Diglin\Ricardo\Enums\Warranty::FOLLOW_CONDITION) {
                    $warrantyText = $this->getSalesOptions()->getProductWarrantyCondition();
                }
                break;
            }
        }

        return $this->escapeHtml($warrantyText);
    }

    /**
     * Get Direct Price of the product
     *
     * @return float|mixed|string
     */
    public function getProductPrice()
    {
        $helper = Mage::helper('diglin_ricento');

        $priceSourceAttributeCode = $this->getSalesOptions()->getPriceSourceAttributeCode();
        $priceChange = $this->getSalesOptions()->getPriceChange();
        $priceChangeType = $this->getSalesOptions()->getPriceChangeType();

        $price = $this->getProduct()->getData($priceSourceAttributeCode);
        $price = $helper->calculatePriceChange($price, $priceChangeType, $priceChange);

        $price = $helper->formatPrice($price);

        return $price;
    }

    /**
     * @return string
     */
    public function getSalesAuctionStartPrice()
    {
        return $this->_getPrice($this->getSalesOptions()->getSalesAuctionStartPrice());
    }

    /**
     * Retrieve list of gallery images
     *
     * @return array|Varien_Data_Collection
     */
    public function getGalleryImages()
    {
        return $this->getProduct()->getMediaGalleryImages();
    }

    /**
     * Retrieve gallery url
     *
     * @param null|Varien_Object $image
     * @return string
     */
    public function getGalleryUrl($image = null)
    {
        $params = array('id' => $this->getProduct()->getId());
        if ($image) {
            $params['image'] = $image->getValueId();
        }
        return $this->getUrl('catalog/product/gallery', $params);
    }
}