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
 * Resource Model of Sales_Options
 */
class Diglin_Ricento_Model_Resource_Sales_Options extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Sales_Options Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/sales_options', 'entity_id');
    }
}