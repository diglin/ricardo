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
    /**
     * @var array
     */
    protected $_days = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_days)) {
            $partnerConfiguration = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getPartnerConfigurations();

            // Default duration
            $duration = array(1);

            if (isset($partnerConfiguration['MaxSellingDuration'])) {
                $duration = range($partnerConfiguration['MinSellingDuration'], $partnerConfiguration['MaxSellingDuration'], 1);
            }

            foreach ($duration as $day) {
                $this->_days[$day] = $day;
            }
        }

        return $this->_days;
    }
}