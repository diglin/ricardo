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

    /**
     * Count the number of items not listed
     *
     * @param int $productsListingId
     * @return int
     */
    public function countNotListedItems($productsListingId)
    {
        return $this->_countItems('status NOT IN (\'' . Diglin_Ricento_Helper_Data::STATUS_LISTED . '\')', $productsListingId);
    }

    /**
     * Count the number of items depending of the where clause
     *
     * @param int $productsListingId
     * @param $whereClause
     * @return int
     */
    protected function _countItems($whereClause, $productsListingId = 0)
    {
        $readerConnection = $this->_getReadAdapter();

        $select = $readerConnection->select()
            ->from($this->getTable('diglin_ricento/products_listing_item'), 'product_id')
            ->where('products_listing_id = :id')
            ->where($whereClause);
        $binds  = array('id' => $productsListingId);

        return count($readerConnection->fetchAll($select, $binds));
    }

    /**
     * @param $status
     * @param $productsListingId
     * @return array
     */
    public function getItemsPerStatusProductsListing($status, $productsListingId)
    {
        $readerConnection = $this->_getReadAdapter();

        $select = $readerConnection->select()
            ->from($this->getTable('diglin_ricento/products_listing_item'), 'item_id')
            ->where('products_listing_id = :id')
            ->where('status = :status');
        $binds  = array('id' => $productsListingId, 'status' => $status);

        return $readerConnection->fetchCol($select, $binds);
    }
}