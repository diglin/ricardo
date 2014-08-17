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
 * @method int      getRicardoCategory()
 * @method string   getSalesType()
 * @method string   getPriceSourceAttributeCode()
 * @method float    getPriceChange()
 * @method string   getPriceChangeType()
 * @method int      getSalesAuctionDirectBuy()
 * @method float    getSalesAuctionStartPrice()
 * @method float    getSalesAuctionIncrement()
 * @method string   getScheduleDateStart()
 * @method int      getSchedulePeriodDays()
 * @method int      getScheduleReactivation()
 * @method int      getScheduleCycleMultipleProducts()
 * @method int      getScheduleOverwriteProductDateStart()
 * @method int      getStockManagement()
 * @method int      getCustomizationTemplate()
 * @method int      getPromotionSpace()
 * @method int      getPromotionStartPage()
 * @method int      getProductWarranty()
 * @method string   getProductWarrantyCondition()
 * @method string   getProductCondition()
 * @method string   getProductConditionSourceAttributeCode()
 * @method string   getCreatedAt()
 * @method string   getUpdatedAt()
 * @method Diglin_Ricento_Model_Sales_Options setRicardoCategory(int $ricardoCategory)
 * @method Diglin_Ricento_Model_Sales_Options setSalesType(string $salesType)
 * @method Diglin_Ricento_Model_Sales_Options setPriceSourceAttributeCode(string $priceSourceAttributeCode)
 * @method Diglin_Ricento_Model_Sales_Options setPriceChange(float $priceChange)
 * @method Diglin_Ricento_Model_Sales_Options setPriceChangeType(string $priceChangeType)
 * @method Diglin_Ricento_Model_Sales_Options setSalesAuctionDirectBuy(int $salesAuctionDirectBuy)
 * @method Diglin_Ricento_Model_Sales_Options setSalesAuctionStartPrice(float $salesAuctionStartPrice)
 * @method Diglin_Ricento_Model_Sales_Options setSalesAuctionIncrement(float $salesAuctionIncrement)
 * @method Diglin_Ricento_Model_Sales_Options setScheduleDateStart(string $scheduleDateStart)
 * @method Diglin_Ricento_Model_Sales_Options setSchedulePeriodDays(int $schedulePeriodDays)
 * @method Diglin_Ricento_Model_Sales_Options setScheduleReactivation(int $scheduleReactivation)
 * @method Diglin_Ricento_Model_Sales_Options setScheduleCycleMultipleProducts(int $scheduleCycleMultipleProducts)
 * @method Diglin_Ricento_Model_Sales_Options setScheduleOverwriteProductDateStart(int $scheduleOverwriteProductDateStart)
 * @method Diglin_Ricento_Model_Sales_Options setStockManagement(int $stockManagement)
 * @method Diglin_Ricento_Model_Sales_Options setCustomizationTemplate(int $customizationTemplate)
 * @method Diglin_Ricento_Model_Sales_Options setPromotionSpace(int $promotionSpace)
 * @method Diglin_Ricento_Model_Sales_Options setPromotionStartPage(int $promotionStartPage)
 * @method Diglin_Ricento_Model_Sales_Options setProductWarranty(int $warranty)
 * @method Diglin_Ricento_Model_Sales_Options setProductWarrantyCondtition(string $warrantyCondition)
 * @method Diglin_Ricento_Model_Sales_Options setProductCondition(string $productConditionSource)
 * @method Diglin_Ricento_Model_Sales_Options setProductConditionSourceAttributeCode(string $productConditionSourceAttributeCode)
 * @method Diglin_Ricento_Model_Sales_Options setCreatedAt(string $createdAt)
 * @method Diglin_Ricento_Model_Sales_Options setUpdatedAt(string $updatedAt)
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