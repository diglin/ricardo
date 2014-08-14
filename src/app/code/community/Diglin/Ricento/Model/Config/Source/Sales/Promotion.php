<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Sales_Promotion
 */
class Diglin_Ricento_Model_Config_Source_Sales_Promotion extends Diglin_Ricento_Model_Config_Source_Abstract
{
    protected $_promotions = array();
    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_promotions)) {
            $promotions = Mage::getSingleton('diglin_ricento/api_services_system')->getPromotions(
                \Diglin\Ricardo\Core\Helper::getJsonDate(), \Diglin\Ricardo\Enums\CategoryArticleType::ALL, 1, 1
            );

            $partnerConfiguration = Mage::getSingleton('diglin_ricento/api_services_system')->getPartnerConfigurations();

            if (isset($partnerConfiguration['CurrencyPrefix'])) {
                $ricardoCurrency = $partnerConfiguration['CurrencyPrefix'];
            } else {
                $ricardoCurrency = Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY;
            }

            $helper = Mage::helper('diglin_ricento');
            $store = Mage::app()->getStore();
            $oldCurrency = $store->getCurrentCurrency();
            $store->setCurrentCurrency(Mage::getModel('directory/currency')->load($ricardoCurrency));

            $this->_promotions = array(0 => $helper->__('No package'));

            foreach ($promotions as $promotion) {
                if ($promotion['GroupId'] == \Diglin\Ricardo\Enums\PromotionCode::PREMIUMCATEGORY) {
                    $this->_promotions[$promotion['PromotionId']] = $helper->__($promotion['PromotionLabel']) . ' - ' . $store->formatPrice($promotion['PromotionFee']);
                }
            }

            // Revert currency changes
            $store->setCurrentCurrency($oldCurrency);
        }

        return $this->_promotions;
    }

}