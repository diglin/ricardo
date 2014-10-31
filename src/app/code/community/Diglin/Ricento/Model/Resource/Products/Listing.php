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
     * @param bool $withChildren
     * @return array
     */
    public function getProductIds($listing, $withChildren = true)
    {
        $readerConnection = $this->_getReadAdapter();

        $select = $readerConnection->select()
            ->from($this->getTable('diglin_ricento/products_listing_item'), 'product_id')
            ->where('products_listing_id = :listing_id');

        $bind = array('listing_id' => (int) $listing->getId());

        if (!$withChildren) {
            $select->where('parent_product_id IS NULL');
        }

        return $readerConnection->fetchCol($select, $bind);
    }

    /**
     * @param $productsListingId
     * @return $this
     */
    public function setStatusStop($productsListingId)
    {
        $readerConnection = $this->_getReadAdapter();

        $select = $readerConnection->select()
            ->from(array('pl' => $this->getTable('diglin_ricento/products_listing')))
            ->where('pl.entity_id = :id')
            ->joinLeft(
                array('pli' => $this->getTable('diglin_ricento/products_listing_item')),
                'pli.products_listing_id = pl.entity_id AND pl.status = "listed"',
                array('item_status' => 'pli.status')
            );

        $binds  = array('id' => $productsListingId);

        $rows = $readerConnection->fetchAll($select, $binds);
        $lists = array();

        foreach ($rows as $row) {
            if ($row['item_status'] == Diglin_Ricento_Helper_Data::STATUS_STOPPED) {
                $lists[$row['entity_id']]['stopped'] = true;
            }
            if ($row['item_status'] == Diglin_Ricento_Helper_Data::STATUS_LISTED) {
                $lists[$row['entity_id']]['listed'] = true;
            }
        }

        foreach ($lists as $key => $list) {
            if (!isset($lists[$key]['listed']) && isset($lists[$key]['stopped'])) {
                $this->saveCurrentList($key, array('status' => Diglin_Ricento_Helper_Data::STATUS_STOPPED));
            }
        }

        return $this;
    }

    /**
     * @param int $listId
     * @param array $bind
     * @return int
     */
    public function saveCurrentList($listId, $bind)
    {
        $writeConection = $this->_getWriteAdapter();

        return $writeConection->update(
            $this->getMainTable(),
            $bind,
            array($this->getIdFieldName() . ' = ?' => $listId));
    }
}