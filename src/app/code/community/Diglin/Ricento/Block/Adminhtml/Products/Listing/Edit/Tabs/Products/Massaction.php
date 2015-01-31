<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 */

class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Massaction_Abstract
{

    public function getGridIdsJson()
    {
        if (!$this->getUseSelectAll()) {
            return '';
        }

        $gridIds = $this->getParentBlock()->getCollection()->getColumnValues('item_id');

        if(!empty($gridIds)) {
            return join(",", $gridIds);
        }
        return '';
    }
}