<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Massaction_Abstract
{

    /**
     * We need here ALL product ids, not the item ids
     *
     * @return string
     */
    public function getGridIdsJson()
    {
        if (!$this->getUseSelectAll()) {
            return '';
        }

        /* @var $collection Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection */
        $collection = $this->getParentBlock()->getCollection();

        $idsSelect = clone $collection->getSelect();
        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);

        $idsSelect->columns('product_id', 'main_table');
        $gridIds = $collection->getConnection()->fetchCol($idsSelect);

        if(!empty($gridIds)) {
            return join(",", $gridIds);
        }
        return '';
    }
}