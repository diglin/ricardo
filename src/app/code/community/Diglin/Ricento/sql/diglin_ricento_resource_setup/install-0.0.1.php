<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableApiTokens = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/api_token'));
$tableApiTokens->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('token', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('token_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('expiration_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable' => false))
    ->addColumn('session_duration', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false))
    ->addColumn('session_expiration_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable' => false))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable' => false))
    ->setComment('Tokens for Ricardo API');
$installer->getConnection()->createTable($tableApiTokens);

$tableSalesOptions = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/sales_options'));
$tableSalesOptions->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('ricardo_category', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('sales_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('price_source_attribute_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('price_change', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('default' => 0))
    ->addColumn('price_change_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255)
    ->addColumn('sales_auction_direct_buy', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array('unsigned' => 1))
    ->addColumn('sales_auction_start_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false, 'default' => 0))
    ->addColumn('sales_auction_increment', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false, 'default' => 0))
    ->addColumn('schedule_date_start', Varien_Db_Ddl_Table::TYPE_DATETIME, array('nullable' => false, 'default' => null))
    ->addColumn('schedule_period_days', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('nullable' => false, 'unsigned' => true))
    ->addColumn('schedule_reactivation', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('default' => null, 'unsigned' => true))
    ->addColumn('schedule_cycle_multiple_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false))
    ->addColumn('schedule_overwrite_product_date_start', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array('unsigned' => true))
    ->addColumn('stock_management', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('default' => -1))
    ->addColumn('customization_template', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('promotion_space', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('promotion_start_page', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array('nullable' => false, 'default' => 0))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->setComment('Sales options');
$installer->getConnection()->createTable($tableSalesOptions);

$tableProductListings = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/products_listing'));
$tableProductListings->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('total_active_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'default' => 0, 'nullable' => false))
    ->addColumn('total_inactive_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'default' => 0, 'nullable' => false))
    ->addColumn('total_sold_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'default' => 0, 'nullable' => false))
    ->addColumn('total_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'default' => 0, 'nullable' => false))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array('nullable' => false))
    ->addColumn('sales_options_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, array('unsigned' => true, 'nullable' => false))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing', 'sales_options_id', 'diglin_ricento/sales_options', 'entity_id'),
        'sales_options_id', $installer->getTable('diglin_ricento/sales_options'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('List of products to be published on ricardo platform');
$installer->getConnection()->createTable($tableProductListings);

$tableSyncLogs = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/sync_log'));
$tableSyncLogs->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => false))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, 4)
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addForeignKey($installer->getFkName('diglin_ricento/sync_log', 'products_listing_id', 'diglin_ricento/products_listing', 'entity_id'),
        'products_listing_id', $installer->getTable('diglin_ricento/products_listing'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Ricardo synchronization logs');
$installer->getConnection()->createTable($tableSyncLogs);

$tableProductListingItems = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/products_listing_item'));
$tableProductListingItems->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('sales_options_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'products_listing_id', 'diglin_ricento/products_listing', 'entity_id'),
        'products_listing_id', $installer->getTable('diglin_ricento/products_listing'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addIndex($installer->getIdxName('diglin_ricento/products_listing_item', array('product_id', 'products_listing_id')),
        array('product_id', 'products_listing_id'), array('type' => 'unique'))
    ->setComment('Associated products for product listings');
$installer->getConnection()->createTable($tableProductListingItems);

$installer->endSetup();