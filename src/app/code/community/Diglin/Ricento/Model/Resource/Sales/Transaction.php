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

/**
 * Class Diglin_Ricento_Model_Resource_Sales_Transaction
 */
class Diglin_Ricento_Model_Resource_Sales_Transaction extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('diglin_ricento/sales_transaction', 'transaction_id');
    }

    /**
     * Returns sum of all transaction prices
     *
     * @return string
     */
    public function getTotalSalesValue()
    {
        $result = $this->getReadConnection()->select()
            ->from($this->getMainTable(), array(new Zend_Db_Expr('SUM(total_bid_price)')))
            ->query(Zend_Db::FETCH_NUM)->fetchColumn(0);
        return $result;
    }
}