<?php
/**
 * Diglin GmbH - Switzerland
 * 
 * User: sylvainraye
 * Date: 07.05.14
 * Time: 00:26
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->removeColumn('action'); // @todo temp
        return $this;
    }

    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * Set the link for the grid. (e.g. to reset the filter)
     */
    public function getGridUrl()
    {
        return parent::getGridUrl();
    }

    public function getRowUrl($row)
    {
        return '';
    }
}