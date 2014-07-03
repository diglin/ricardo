<?php
/**
 * Diglin GmbH - Switzerland
 * 
 * User: sylvainraye
 * Date: 23.04.14
 * Time: 22:58
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();


$tableApiTokens = $installer->getConnection()->newTable('ricento_api_tokens');
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

$tableSalesOptions = $installer->getConnection()->newTable('ricardo_sales_options');
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

$tableProductListings = $installer->getConnection()->newTable('ricento_product_listings');
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
    ->addForeignKey('sales_options_id_idxfk', 'sales_options_id', 'ricento_sales_options', 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('List of products to be published on ricardo platform');
$installer->getConnection()->createTable($tableProductListings);

$tableSyncLogs = $installer->getConnection()->newTable('ricento_sync_logs');
$tableSyncLogs->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => false))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, 4)
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addForeignKey('products_listing_id_idxfk', 'products_listing_id', 'ricento_product_listings', 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Ricardo synchronization logs');
$installer->getConnection()->createTable($tableSyncLogs);
/*
CREATE TABLE ricento_products_listing_items
(
entity_id INT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
product_id INT(4) UNSIGNED NOT NULL,
products_listing_id INT(4) UNSIGNED NOT NULL,
sales_options_id INT(4) UNSIGNED NOT NULL,
status VARCHAR(255) NOT NULL,
created_at DATETIME,
updated_at DATETIME,
PRIMARY KEY (entity_id)
) ENGINE=InnoDB COMMENT='Products belonging to the products list and which should be ' CHARACTER SET=utf8;
 */
$tableProductListingItems = $installer->getConnection()->newTable('ricento_products_listing_items');
$tableProductListingItems->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('sales_options_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
    ->addForeignKey('product_id_idxfk', 'product_id', 'catalog_product_entity', 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey('products_listing_id_idxfk_1', 'products_listing_id', 'ricento_product_listings', 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addIndex('ricento_products_listing_items_idx', array('product_id', 'products_listing_id'), array('type' => 'unique'))
    ->setComment('Associated products for product listings');
$installer->getConnection()->createTable($tableProductListingItems);

$installer->endSetup();