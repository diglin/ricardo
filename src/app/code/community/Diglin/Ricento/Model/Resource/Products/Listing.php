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
 * Resource Model of Products_Listing
 */
class Diglin_Ricento_Model_Resource_Products_Listing extends Mage_Core_Model_Resource_Db_Abstract
{


    /**
     * Products_Listing Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/products_listing', 'entity_id');
    }


    /**
     * Get product ids of listing items
     *
     * @param Diglin_Ricento_Model_Products_Listing $listing
     * @return array
     */
    public function getProductIds($listing)
    {
        $select = $this->_getWriteAdapter()->select()
            ->from($this->getTable('diglin_ricento/products_listing_item'), 'product_id')
            ->where('products_listing_id = :listing_id');
        $bind = array('listing_id' => (int)$listing->getId());

        return $this->_getWriteAdapter()->fetchCol($select, $bind);
    }
}