<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Validate_Products_Item extends Zend_Validate_Abstract
{
    const LENGTH_PRODUCT_TITLE = 40;
    const LENGTH_PRODUCT_SUBTITLE = 60;
    const LENGTH_PRODUCT_DESCRIPTION = 65000;

    const BUYNOW_MINPRICE_FIXPRICE = 0.05;
    const BUYNOW_MAXPRICE_FIXPRICE = 2999.95;
    const BUYNOW_MINPRICE_AUCTIONPRICE = 0.1;

    const PERIOD_DAYS_MIN = 1; // in days
    const PERIOD_DAYS_MAX = 10; // in days

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  Diglin_Ricento_Model_Products_Listing_Item $item
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid($item)
    {
        if (!$item instanceof Diglin_Ricento_Model_Products_Listing_Item) {
            return false;
        }

        $item->setLoadFallbackOptions(true);

        $result = new Varien_Object();
        $result->setHasError(false);
        $result->setHasWarning(false);

        // Validate the currency
        $currencyCode = Mage::app()->getWebsite($item->getProductsListing()->getWebsiteId())->getBaseCurrencyCode();
        if ($currencyCode != Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY) {
            // Warning - Ricardo supports only CHF currency
        }

        // Validate name
        $strLen = new Zend_Validate_StringLength(array('min' => 1, 'max' => self::LENGTH_PRODUCT_TITLE));
        if (!$strLen->isValid($item->getProductTitle(false))) {
            // warning - content will be cut when exporting to ricardo
        }

        // Validate subtitle
        $strLen = new Zend_Validate_StringLength(array('max' => self::LENGTH_PRODUCT_SUBTITLE));
        if (!$strLen->isValid($item->getProductSubtitle(false))) {
            // warning - content will be cut when exporting to ricardo
        }

        // Validate description
        $strLen = new Zend_Validate_StringLength(array('min' => 1, 'max' => self::LENGTH_PRODUCT_DESCRIPTION));
        if (!$strLen->isValid($item->getProductDescription(false))) {
            // warning - content will be cut when exporting to ricardo
        }

        // Validate custom options
        // @todo to check
        if (!$item->getProduct()->hasOptions()) {
            // warning - no option will be send to ricardo.ch
        }

        // Validate Category exists
        $ricardoCategoryId = $item->getCategory();
        if (!$ricardoCategoryId) {
            // error - category cannot be empty
        }

        // Validate Payment and Shipping Rule
        $methodValidator = new Diglin_Ricento_Model_Validate_Rules_Methods();
        $rules = $item->getShippingPaymentRule();
        $methods = array(
            'shipping' => $rules->getShippingMethod(),
            'payment' => $rules->getPaymentMethods()
        );

        if (!$methodValidator->isValid($methods)) {
            // Error - combination respect mandatory
        }


        // Validate price against buy now price > 0.05 or 0.1
        $salesOptions = $item->getSalesOptions();
        if ($salesOptions->getSalesType() == Diglin_Ricento_Model_Config_Source_Sales_Type::AUCTION && $salesOptions->getSalesAuctionDirectBuy()) {
            $startPrice = $salesOptions->getSalesAuctionStartPrice();
            $minPrice = ($startPrice < 0.1) ? 0.1 : $startPrice;

            $greatherThanValidator  = new Zend_Validate_GreaterThan(array('min' => $minPrice));
            if (!$greatherThanValidator->isValid($item->getPrice())) {
                // Error - Price not allowed
            }
        }

        if ($salesOptions->getSalesType() == Diglin_Ricento_Model_Config_Source_Sales_Type::BUY_NOW) {
            $betweenValidator  = new Zend_Validate_Between(
                array(
                    'min' => self::BUYNOW_MINPRICE_FIXPRICE,
                    'max' => self::BUYNOW_MAXPRICE_FIXPRICE,
                    'inclusive' => true
                )
            );
            if (!$betweenValidator->isValid($item->getPrice())) {
                // Error - Price not allowed
            }
        }

        // Validate Ending Date
        $period = $item->getSalesOptions()->getSchedulePeriodDays();
        $betweenValidator  = new Zend_Validate_Between(
            array(
                'min' => self::PERIOD_DAYS_MIN,
                'max' => self::PERIOD_DAYS_MAX,
                'inclusive' => true
            )
        );
        if (!$betweenValidator->isValid($period)) {
            // Error - Period too long
        }

        // Validate Inventory

        // In Stock or not?

        if (!$item->getStockItem()->getManageStock()) {

        }


        $item->setLoadFallbackOptions(false);

        return true;
    }
}