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
 * Collection of Products_Listing_Item
 */
class Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Products_Listing_Item Collection Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/products_listing_item');
    }

    /**
     * @param $status
     * @return $this
     */
    public function updateStatusToAll($status)
    {
        $connection = $this->getConnection();
        $connection->update(
            $this->getTable('diglin_ricento/products_listing_item'),
                array('status' => $status),
                array('item_id IN (?)' => $this->getAllIds())
        );

        return $this;
    }
}