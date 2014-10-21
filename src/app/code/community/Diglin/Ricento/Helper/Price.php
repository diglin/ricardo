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
class Diglin_Ricento_Helper_Price extends Mage_Core_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_oldCurrency = Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY;

    /**
     * Format the price with the ricardo supported currencies
     *
     * @param float $value
     * @param int $websiteId
     * @return string
     */
    public function formatPrice($value, $websiteId = null)
    {
        $this->startCurrencyEmulation($websiteId);

        $value = Mage::app()->getWebsite($websiteId)->getDefaultStore()->formatPrice($value);

        $this->stopCurrencyEmulation($websiteId);

        return $value;
    }

    /**
     * Emulate CHF currency in case the current store settings is different as the allowed currency/ies
     *
     * @param int $websiteId
     * @return $this
     */
    public function startCurrencyEmulation($websiteId = null)
    {
        $partnerConfiguration = Mage::getSingleton('diglin_ricento/api_services_system')
            ->setCurrentWebsite($websiteId)
            ->getPartnerConfigurations();

        if (isset($partnerConfiguration['CurrencyPrefix'])) {
            $ricardoCurrency = $partnerConfiguration['CurrencyPrefix'];
        } else {
            $ricardoCurrency = Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY;
        }

        $store = Mage::app()->getWebsite($websiteId)->getDefaultStore();
        $this->_oldCurrency = $store->getCurrentCurrency();
        $store->setCurrentCurrency(Mage::getModel('directory/currency')->load($ricardoCurrency));

        return $this;
    }

    /**
     * Revert the changes done regarding the currency of the current store
     *
     * @param int $websiteId
     * @return $this
     */
    public function stopCurrencyEmulation($websiteId = null)
    {
        Mage::app()->getWebsite($websiteId)->getDefaultStore()->setCurrentCurrency($this->_oldCurrency);

        return $this;
    }

    /**
     * Calculate the price change depending on the type and value of the change to apply
     *
     * @param float|int $price
     * @param string $priceChangeType
     * @param float|int $priceChange
     * @return float
     */
    public function calculatePriceChange($price, $priceChangeType, $priceChange)
    {
        switch ($priceChangeType) {
            case Diglin_Ricento_Model_Config_Source_Sales_Price_Method::PRICE_TYPE_DYNAMIC_NEG:
                $price -= ($price * $priceChange / 100);
                break;
            case Diglin_Ricento_Model_Config_Source_Sales_Price_Method::PRICE_TYPE_DYNAMIC_POS:
                $price += ($price * $priceChange / 100);
                break;
            case Diglin_Ricento_Model_Config_Source_Sales_Price_Method::PRICE_TYPE_FIXED_NEG:
                $price -= $priceChange;
                break;
            case Diglin_Ricento_Model_Config_Source_Sales_Price_Method::PRICE_TYPE_FIXED_POS:
                $price += $priceChange;
                break;
            case Diglin_Ricento_Model_Config_Source_Sales_Price_Method::PRICE_TYPE_NOCHANGE:
            default:
                break;
        }

        return $price;
    }

    /**
     * Calculate configurable product selection price
     *
     * @param   array $priceInfo
     * @param   float $productPrice
     * @return  float
     */
    public function calcSelectionPrice($priceInfo, $productPrice)
    {
        if ($priceInfo['is_percent']) {
            $ratio = $priceInfo['pricing_value']/100;
            $price = $productPrice * $ratio;
        } else {
            $price = $priceInfo['pricing_value'];
        }
        return $price;
    }
}