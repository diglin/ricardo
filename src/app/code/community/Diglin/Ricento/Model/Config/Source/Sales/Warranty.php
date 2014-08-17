<?php

/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Config_Source_Sales_Warranty extends Diglin_Ricento_Model_Config_Source_Abstract
{
    protected $_warranties = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_warranties)) {
            $warranties = Mage::getSingleton('diglin_ricento/api_services_system')->getWarranties();

            foreach ($warranties as $warranty) {
                $this->_warranties[$warranty['WarrantyId']] = $warranty['WarrantyConditionText'];
            }
        }

        return $this->_warranties;
    }
}