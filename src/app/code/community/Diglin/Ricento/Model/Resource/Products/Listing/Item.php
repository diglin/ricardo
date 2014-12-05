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
    public function countPendingItems($productsListingId)
    {
        return $this->_countItems('status IN (\'' . Diglin_Ricento_Helper_Data::STATUS_PENDING . '\', \'' . Diglin_Ricento_Helper_Data::STATUS_ERROR .'\', \'' . Diglin_Ricento_Helper_Data::STATUS_STOPPED .'\')', $productsListingId);
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
        return $this->_countItems('status IN (\'' . Diglin_Ricento_Helper_Data::STATUS_READY . '\')', $productsListingId);
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

    /**
     * Stop parent product item if all children products are stopped
     * Probably deprecated because the parent configurable product should not have a changing status
     *
     * @param $productsListingId
     * @return $this
     */
    public function setParentStatusStop($productsListingId)
    {
        $readerConnection = $this->_getReadAdapter();

        $select = $readerConnection->select()
            ->from(array('pli' => $this->getTable('diglin_ricento/products_listing_item')), array( 'parent_id' => 'pli.parent_item_id', 'item_status' => 'pli.status'))
            ->where('pli.products_listing_id = :id')
            ->where('pli.parent_item_id IS NOT NULL')
            ->joinLeft(array('plib' => $this->getTable('diglin_ricento/products_listing_item')), 'plib.item_id = pli.parent_item_id AND plib.status = "listed"');

        $binds  = array('id' => $productsListingId);

        $items = $readerConnection->fetchAll($select, $binds);
        $parents = array();

        foreach ($items as $item) {
            if ($item['item_status'] == Diglin_Ricento_Helper_Data::STATUS_STOPPED) {
                $parents[$item['parent_id']]['stopped'] = true;
            }
            if ($item['item_status'] == Diglin_Ricento_Helper_Data::STATUS_LISTED) {
                $parents[$item['parent_id']]['listed'] = true;
            }
        }

        foreach ($parents as $key => $parent) {
            if (!isset($parents[$key]['listed']) && isset($parents[$key]['stopped'])) {
                $this->saveCurrentItem($key, array('status' => Diglin_Ricento_Helper_Data::STATUS_STOPPED));
            }
        }

        return $this;
    }
}