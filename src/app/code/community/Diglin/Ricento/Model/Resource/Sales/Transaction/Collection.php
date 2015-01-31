<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Diglin_Ricento_Model_Resource_Sales_Transaction_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_groupByProduct = false;
    protected $_isProductTableJoined = false;
    protected $_isProductAttributeJoined = array();

    /**
     * Sales_Options Collection Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/sales_transaction');
    }
    /**
     * Get flag if collection is grouped by product
     *
     * @return boolean
     */
    public function isGroupByProduct()
    {
        return $this->_groupByProduct;
    }

    /**
     * Set flag that collection is grouped by product
     *
     * @param boolean $groupByProduct
     * @return $this
     */
    public function setGroupByProduct($groupByProduct)
    {
        $this->_groupByProduct = $groupByProduct;
        return $this;
    }

    /**
     * Join product information
     *
     * @return $this
     */
    public function joinProducts()
    {
        $this->_joinProductAttribute('name');
        $this->_joinProductAttribute('price');
        $this->_joinProductSku();
        return $this;
    }
    /**
     * Joins product attribute value
     *
     * @param string $attributeCode
     * @return $this
     * @throws Mage_Core_Exception
     */
    protected function _joinProductAttribute($attributeCode)
    {
        if (empty($this->_isProductAttributeJoined[$attributeCode])) {
            $entityTypeId = Mage::getResourceModel('catalog/config')
                ->getEntityTypeId();
            $attribute = Mage::getModel('catalog/entity_attribute')
                ->loadByCode($entityTypeId, $attributeCode);

            $storeId = Mage::app()->getStore()->getId();

            $tableAlias = "product_{$attributeCode}_table";
            $fieldAlias = "product_{$attributeCode}";
            $this->getSelect()
                ->join(
                    array($tableAlias => $attribute->getBackendTable()),
                    "{$tableAlias}.entity_id=main_table.product_id" .
                    " AND {$tableAlias}.store_id=" . $storeId .
                    " AND {$tableAlias}.attribute_id=" . $attribute->getId().
                    " AND {$tableAlias}.entity_type_id=" . $entityTypeId,
                    array($fieldAlias => 'value')
                );

            $this->_isProductAttributeJoined[$attributeCode] = true;
        }
        return $this;
    }

    /**
     * Join product entity table to select SKU
     *
     * @return $this
     */
    protected function _joinProductSku()
    {
        if (!$this->_isProductTableJoined) {

            $this->getSelect()
                ->join(
                    array('product_table' => $this->getTable('catalog/product')),
                    "product_table.entity_id=main_table.product_id",
                    array('product_sku' => 'sku')
                );
            $this->_isProductTableJoined = true;
        }
        return $this;
    }

    /**
     * If the collection is grouped by products, count the distinct product_ids
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        if ($this->isGroupByProduct()) {
            $countSelect->reset(Zend_Db_Select::COLUMNS);
            $countSelect->reset(Zend_Db_Select::GROUP);
            $countSelect->columns('COUNT(DISTINCT main_table.product_id)');
        }
        return $countSelect;
    }

    /**
     * Prepares collection for revenue report by month
     */
    public function prepareReport()
    {
        $dateStart = Mage::app()->getLocale()->date();
        $dateEnd = clone $dateStart;
        $dateStart->subYear(1);

        $dateStart->setHour(0);
        $dateStart->setMinute(0);
        $dateStart->setSecond(0);

        $dateEnd->setHour(23);
        $dateEnd->setMinute(59);
        $dateEnd->setSecond(59);

        $this->getSelect()->reset(Zend_Db_Select::COLUMNS);
        $this->getSelect()->columns(
            array(
                'range'    => 'DATE_FORMAT(sold_at, "%Y-%m")',
                'revenue'  => 'SUM(main_table.total_bid_price)',
            ))
            ->where(
                $this->_getConditionSql('sold_at', array("from" => $dateStart, "to" => $dateEnd, 'datetime' => true))
            )
            ->group(
                $this->getConnection()->getDateFormatSql('sold_at', '%Y-%m')
            );
    }
}