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

class Diglin_Ricento_Model_Sales_Method_Shipping extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * Carrier's code
     *
     * @var string
     */
    protected $_code = 'ricento';

    /**
     * Whether this carrier has fixed rates calculation
     *
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * Collect and get rates
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result|bool|null
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active') || !Mage::helper('diglin_ricento')->isEnabled()) {
            return false;
        }

        /**
         * @todo finish to implement if needed
         * Be aware the packages can have DeliveryCost value but also subpackages
         *
         * At the moment, we do not use those values as we get the shipping information and payment from the ricardo API
         */

        $packages = Mage::getSingleton('diglin_ricento/config_source_rules_shipping_packages')->toOptionHash();

        /** @var Mage_Shipping_Model_Rate_Result $result */
        $result = Mage::getModel('shipping/rate_result');

        foreach ($this->getAllowedMethods() as $conditionId => $conditionText) {

            /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
            $rate = Mage::getModel('shipping/rate_result_method');

            $rate->setCarrier($this->_code);
            $rate->setCarrierTitle($this->getConfigData('title'));
            $rate->setMethod($conditionId);
            $rate->setMethodTitle($conditionText);
            $rate->setPrice($packages[$conditionId]); //@todo change price for each sub package
            $rate->setCost(0);

            $result->append($rate);
        }

        return $result;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return Mage::getSingleton('diglin_ricento/config_source_rules_shipping')->toOptionHash();
    }
}