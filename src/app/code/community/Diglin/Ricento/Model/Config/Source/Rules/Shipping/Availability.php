<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Availability
 */
class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Availability extends Diglin_Ricento_Model_Config_Source_Abstract
{
    protected $_availabilities = array();

    public function toOptionHash()
    {
        if (empty($this->_availabilities) && Mage::helper('diglin_ricento')->isConfigured()) {
            $availabilities = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getAvailabilities();

            foreach ($availabilities as $availabilitiy)
                if (isset($availabilitiy['AvailabilityId'])) {
                    $this->_availabilities[$availabilitiy['AvailabilityId']] = $availabilitiy['AvailabilityText'];
                }
        }
        return $this->_availabilities;
    }
}