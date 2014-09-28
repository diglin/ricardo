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