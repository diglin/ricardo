<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
     * Count the number of items listed
     *
     * @param int $productsListingId
     * @return int
     */
    public function countListedItems($productsListingId)
    {
        return $this->_countItems('status IN (\'' . Diglin_Ricento_Helper_Data::STATUS_LISTED . '\')', $productsListingId);
    }

    /**
     * Count the number of items sold
     *
     * @param int $productsListingId
     * @return int
     */
    public function countSoldItems($productsListingId)
    {
        return $this->_countItems('status IN (\'' . Diglin_Ricento_Helper_Data::STATUS_SOLD . '\')', $productsListingId);
    }

    /**
     * Count the number of items ready to list
     *
     * @param int $productsListingId
     * @return int
     */
    public function coundReadyTolist($productsListingId)
    {
        return $this->_countItems('status IN (\'' . Diglin_Ricento_Helper_Data::STATUS_READY . '\', \'' . Diglin_Ricento_Helper_Data::STATUS_PENDING . '\')', $productsListingId);
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

    /**
     * @param int $itemId
     * @param array $bind
     * @return int
     */
    public function saveCurrentItem($itemId, $bind)
    {
        $writeConection = $this->_getWriteAdapter();

        return $writeConection->update(
            $this->getMainTable(),
            $bind,
            array($this->getIdFieldName() . ' = ?' => $itemId));
    }
}