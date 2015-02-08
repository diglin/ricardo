<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Sales_Currency
 */
class Diglin_Ricento_Model_Config_Source_Sales_Currency extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        return array(
            strtolower(Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY) => Diglin_Ricento_Helper_Data::ALLOWED_CURRENCY
        );
    }

    public function toOptionArray()
    {
        return $this->toOptionHash();
    }
}