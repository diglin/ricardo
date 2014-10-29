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
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Add a column to sales/quote table
 */
$salesQuoteTable = $installer->getTable('sales/quote');

$installer->getConnection()->addColumn($salesQuoteTable, 'is_ricardo', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'Is ricardo.ch Transaction'));

/**
 * Add a column to sales/order table
 */
$salesOrderTable = $installer->getTable('sales/order');

$installer->getConnection()->addColumn($salesOrderTable, 'is_ricardo', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'Is ricardo.ch Transaction'));

$installer->endSetup();