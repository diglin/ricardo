<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Config_Source_Sales_Days extends Diglin_Ricento_Model_Config_Source_Abstract
{
    public function toOptionHash()
    {
        $partnerConfiguration = Mage::getSingleton('diglin_ricento/api_services_system')->getPartnerConfigurations();

        $duration = array(2);

        if (isset($partnerConfiguration['MaxSellingDuration'])) {
            if ($partnerConfiguration['MinSellingDuration'] == 1) {
                $minDuration = $partnerConfiguration['MinSellingDuration'] + 1;
            } else {
                $minDuration = $partnerConfiguration['MinSellingDuration'];
            }
            $duration = range($minDuration, $partnerConfiguration['MaxSellingDuration'], 2);
        }
        return $duration;
    }
}