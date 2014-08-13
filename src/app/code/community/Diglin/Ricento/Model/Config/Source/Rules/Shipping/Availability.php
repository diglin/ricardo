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
 * Class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Availability
 */
class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Availability extends Diglin_Ricento_Model_Config_Source_Abstract
{
    protected $_availabilities = array();

    public function toOptionHash()
    {
        if (empty($this->_availabilities)) {
            $availabilities = Mage::getSingleton('diglin_ricento/api_services_system')->getAvailabilities();

            foreach ($availabilities as $availabilitiy)
                if (isset($availabilitiy['AvailabilityId'])) {
                    $this->_availabilities[$availabilitiy['AvailabilityId']] = $availabilitiy['AvailabilityText'];
                }
        }
        return $this->_availabilities;
    }
}