<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Diglin_Ricento_Helper_Price extends Mage_Core_Helper_Abstract
{
    /**
     * Format the price with the ricardo supported currencies
     *
     * @param float $value
     * @param int $store
     * @return string
     */
    public function formatPrice($value, $store = null)
    {
        $this->startCurrencyEmulation();

        $value = Mage::app()->getStore($store)->formatPrice($value);

        $this->stopCurrencyEmulation();

        return $value;
    }

    /**
     * Emulate CHF currency in case the current store settings is different as the allowed currency/ies
     *
     * @return $this
     */
    public function startCurrencyEmulation()
    {
        $partnerConfiguration = Mage::getSingleton('diglin_ricento/api_services_system')->getPartnerConfigurations();

        if (isset($partnerConfiguration['CurrencyPrefix'])) {
            $ricardoCurrency = $partnerConfiguration['CurrencyPrefix'];
        } else {
            $ricardoCurrency = Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY;
        }

        $store = Mage::app()->getStore();
        $this->_oldCurrency = $store->getCurrentCurrency();
        $store->setCurrentCurrency(Mage::getModel('directory/currency')->load($ricardoCurrency));

        return $this;
    }

    /**
     * Revert the changes done regarding the currency of the current store
     *
     * @return $this
     */
    public function stopCurrencyEmulation()
    {
        Mage::app()->getStore()->setCurrentCurrency($this->_oldCurrency);

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
}