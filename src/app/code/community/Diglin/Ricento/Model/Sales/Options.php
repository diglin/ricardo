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
 * @method string   getProductWarrantyDescriptionDe()
 * @method string   getProductWarrantyDescriptionFr()
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
 * @method Diglin_Ricento_Model_Sales_Options setProductWarrantyDescriptionFr(string $warrantyDescriptionFr)
 * @method Diglin_Ricento_Model_Sales_Options setProductWarrantyDescriptionDe(string $warrantyDescriptionDe)
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

    /**
     * @param string $lang
     * @return string
     */
    public function getProductWarrantyDescription($lang = Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG)
    {
        return $this->_getDescriptionLangMethod($lang, 'getProductWarrantyDescription');
    }

    /**
     * @param string $lang
     * @param string $method
     * @return string
     */
    protected function _getDescriptionLangMethod($lang = Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG, $method)
    {
        $supportedLang = Mage::helper('diglin_ricento')->getSupportedLang();
        $lang = strtolower($lang);

        if (in_array($lang, $supportedLang)) {
            $method = $method . ucwords($lang);
            return $this->$method();
        }
        return '';
    }
}