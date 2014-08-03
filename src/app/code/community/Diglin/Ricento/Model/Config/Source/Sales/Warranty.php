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
    const FOLLOW_CONDITION = 0;
    const NONE = 1;

    /**
     * @return array
     */
    public function toOptionHash()
    {
        $warranties = Mage::getSingleton('diglin_ricento/api_services_system')->getWarranties();

        $result = array();
        foreach ($warranties as $warranty) {
            $result[$warranty['WarrantyId']] = $warranty['WarrantyConditionText'];
        }

        return $result;
    }
}