<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Collection of Sales_Options
 */
class Diglin_Ricento_Model_Resource_Sales_Options_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{


    /**
     * Sales_Options Collection Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/sales_options');
    }


}