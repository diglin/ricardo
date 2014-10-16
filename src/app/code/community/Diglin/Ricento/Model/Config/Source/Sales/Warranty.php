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

/**
 * Class Diglin_Ricento_Model_Config_Source_Sales_Warranty
 */
class Diglin_Ricento_Model_Config_Source_Sales_Warranty extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @var array
     */
    protected $_warranties = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_warranties)) {
            $warranties = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getWarranties();

            foreach ($warranties as $warranty) {
                $this->_warranties[$warranty['WarrantyId']] = $warranty['WarrantyConditionText'];
            }
        }

        return $this->_warranties;
    }
}