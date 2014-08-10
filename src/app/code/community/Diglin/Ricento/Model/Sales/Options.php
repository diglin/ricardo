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
 * Sales_Options Model
 *
 * @method int      getRicardoCategory() getRicardoCategory()
 * @method string   getSalesType() getSalesType()
 * @method string      getPriceSourceAttributeCode() getPriceSourceAttributeCode()
 * @method float    getPriceChange() getPriceChange()
 * @method string   getPriceChangeType() getPriceChangeType()
 * @method int      getSalesAuctionDirectBuy() getSalesAuctionDirectBuy()
 * @method float    getSalesAuctionStartPrice() getSalesAuctionStartPrice()
 * @method float    getSalesAuctionIncrement() getSalesAuctionIncrement()
 * @method string   getScheduleDateStart() getScheduleDateStart()
 * @method int      getSchedulePeriodDays() getSchedulePeriodDays()
 * @method int      getScheduleReactivation() getScheduleReactivation()
 * @method int      getScheduleCycleMultipleProducts() getScheduleCycleMultipleProducts()
 * @method int      getScheduleOverwriteProductDateStart() getScheduleOverwriteProductDateStart()
 * @method int      getStockManagement() getStockManagement()
 * @method int      getCustomizationTemplate() getCustomizationTemplate()
 * @method int      getPromotionSpace() getPromotionSpace()
 * @method int      getPromotionStartPage() getPromotionStartPage()
 * @method string   getProductConditionSource() getProductConditionSource()
 * @method string   getProductConditionSourceAttributeCode() getProductConditionSourceAttributeCode()
 * @method string   getCreatedAt() getCreatedAt()
 * @method string   getUpdatedAt() getUpdatedAt()
 * @method Diglin_Ricento_Model_Sales_Options setRicardoCategory() setRicardoCategory(int $ricardoCategory)
 * @method Diglin_Ricento_Model_Sales_Options setSalesType() setSalesType(string $salesType)
 * @method Diglin_Ricento_Model_Sales_Options setPriceSourceAttributeCode() setPriceSourceAttributeCode(string $priceSourceAttributeCode)
 * @method Diglin_Ricento_Model_Sales_Options setPriceChange() setPriceChange(float $priceChange)
 * @method Diglin_Ricento_Model_Sales_Options setPriceChangeType() setPriceChangeType(string $priceChangeType)
 * @method Diglin_Ricento_Model_Sales_Options setSalesAuctionDirectBuy() setSalesAuctionDirectBuy(int $salesAuctionDirectBuy)
 * @method Diglin_Ricento_Model_Sales_Options setSalesAuctionStartPrice() setSalesAuctionStartPrice(float $salesAuctionStartPrice)
 * @method Diglin_Ricento_Model_Sales_Options setSalesAuctionIncrement() setSalesAuctionIncrement(float $salesAuctionIncrement)
 * @method Diglin_Ricento_Model_Sales_Options setScheduleDateStart() setScheduleDateStart(string $scheduleDateStart)
 * @method Diglin_Ricento_Model_Sales_Options setSchedulePeriodDays() setSchedulePeriodDays(int $schedulePeriodDays)
 * @method Diglin_Ricento_Model_Sales_Options setScheduleReactivation() setScheduleReactivation(int $scheduleReactivation)
 * @method Diglin_Ricento_Model_Sales_Options setScheduleCycleMultipleProducts() setScheduleCycleMultipleProducts(int $scheduleCycleMultipleProducts)
 * @method Diglin_Ricento_Model_Sales_Options setScheduleOverwriteProductDateStart() setScheduleOverwriteProductDateStart(int $scheduleOverwriteProductDateStart)
 * @method Diglin_Ricento_Model_Sales_Options setStockManagement() setStockManagement(int $stockManagement)
 * @method Diglin_Ricento_Model_Sales_Options setCustomizationTemplate() setCustomizationTemplate(int $customizationTemplate)
 * @method Diglin_Ricento_Model_Sales_Options setPromotionSpace() setPromotionSpace(int $promotionSpace)
 * @method Diglin_Ricento_Model_Sales_Options setPromotionStartPage() setPromotionStartPage(int $promotionStartPage)
 * @method Diglin_Ricento_Model_Sales_Options setProductConditionSource() setProductConditionSource(string $productConditionSource)
 * @method Diglin_Ricento_Model_Sales_Options setProductConditionSourceAttributeCode() setProductConditionSourceAttributeCode(string $productConditionSourceAttributeCode)
 * @method Diglin_Ricento_Model_Sales_Options setCreatedAt() setCreatedAt(string $createdAt)
 * @method Diglin_Ricento_Model_Sales_Options setUpdatedAt() setUpdatedAt(string $updatedAt)
 */
class Diglin_Ricento_Model_Sales_Options extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'sales_options';

    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() in this case
     * @var string
     */
    protected $_eventObject = 'sales_options';

    /**
     * Sales_Options Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/sales_options');
    }

    /**
     * Set date of last update
     *
     * @return Diglin_Ricento_Model_Sales_Options
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }

}