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
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$transactionTable = $installer->getTable('diglin_ricento/sales_transaction');

/**
 * Add a column to sales/quote_item table
 */
//$salesQuoteTable = $installer->getTable('sales/quote_item');
//
//$installer->getConnection()->addColumn($salesQuoteTable, 'ricardo_transaction_id', array(
//    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
//    'nullable' => true,
//    'unsigned' => true,
//    'comment' => 'Ricardo Transaction Id'));
//
//$installer->getConnection()->addForeignKey(
//    $installer->getFkName($salesQuoteTable, 'ricardo_transaction_id', $transactionTable, 'transaction_id'),
//    $salesQuoteTable, 'ricardo_transaction_id', $transactionTable, 'transaction_id',
//    Varien_Db_Ddl_Table::ACTION_SET_NULL
//);
//
///**
// * Add a column to sales/order_item table
// */
//$salesOrderItemTable = $installer->getTable('sales/order_item');
//
//$installer->getConnection()->addColumn($salesOrderItemTable, 'ricardo_transaction_id', array(
//    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
//    'nullable' => true,
//    'unsigned' => true,
//    'comment' => 'Ricardo Transaction Id'));
//
//$installer->getConnection()->addForeignKey(
//    $installer->getFkName($salesOrderItemTable, 'ricardo_transaction_id', $transactionTable, 'transaction_id'),
//    $salesOrderItemTable, 'ricardo_transaction_id', $transactionTable, 'transaction_id',
//    Varien_Db_Ddl_Table::ACTION_SET_NULL
//);

$installer->endSetup();