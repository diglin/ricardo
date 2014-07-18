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
 * Resource Model of Products_Listing_Item
 */
class Diglin_Ricento_Model_Resource_Products_Listing_Item extends Mage_Core_Model_Resource_Db_Abstract
{


    /**
     * Products_Listing_Item Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/products_listing_item', 'item_id');
    }


}