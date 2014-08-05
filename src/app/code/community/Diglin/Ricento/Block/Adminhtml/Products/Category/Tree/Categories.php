<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Category_Tree_Categories extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
    /**
     * @return bool
     */
    public function isReadonly()
    {
        return false;
    }

    /**
     * Return array with category IDs which the product is assigned to
     *
     * @return array
     */
    protected function getCategoryIds()
    {
        return array();
    }

}