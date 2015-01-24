<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$apiTokenTable = $installer->getTable('diglin_ricento/api_token');
$salesOptionsTable = $installer->getTable('diglin_ricento/sales_options');
$shippingPaymentRuleTable = $installer->getTable('diglin_ricento/shipping_payment_rule');
$productListingTable = $installer->getTable('diglin_ricento/products_listing');
$productListingItemTable = $installer->getTable('diglin_ricento/products_listing_item');

$syncJobTable = $installer->getTable('diglin_ricento/sync_job');
$listingLogTable = $installer->getTable('diglin_ricento/listing_log');
$transactionTable = $installer->getTable('diglin_ricento/sales_transaction');
$syncJobListingTable = $installer->getTable('diglin_ricento/sync_job_listing');

$tableApiTokens = $installer->getConnection()->newTable($apiTokenTable);
$tableApiTokens->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('token', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('token_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array('unsigned' => true, 'nullable' => false))
    ->addColumn('expiration_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable' => false))
    ->addColumn('session_duration', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false))
    ->addColumn('session_expiration_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable' => false))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->setComment('Tokens for ricardo.ch API');
$installer->getConnection()->createTable($tableApiTokens);

$tableSalesOptions = $installer->getConnection()->newTable($salesOptionsTable);
$tableSalesOptions->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('ricardo_category', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('nullable' => true, 'unsigned' => false, 'default' => 0))
    ->addColumn('sales_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('price_source_attribute_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => true))
    ->addColumn('price_change', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('default' => 0))
    ->addColumn('price_change_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255)
    ->addColumn('sales_auction_direct_buy', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array('unsigned' => 1))
    ->addColumn('sales_auction_start_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false, 'default' => 0))
    ->addColumn('sales_auction_increment', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false, 'default' => 0))
    ->addColumn('schedule_date_start', Varien_Db_Ddl_Table::TYPE_DATETIME, array('nullable' => false, 'default' => null))
    ->addColumn('schedule_period_days', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('nullable' => false, 'unsigned' => true))
    ->addColumn('schedule_reactivation', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('default' => null, 'unsigned' => true))
    ->addColumn('schedule_cycle_multiple_products', Varien_Db_Ddl_Table::TYPE_SMALLINT, 4, array('nullable' => true, 'default' => null))
    ->addColumn('schedule_overwrite_product_date_start', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array('unsigned' => true))
    ->addColumn('stock_management', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('default' => -1))
    ->addColumn('customization_template', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => true, 'unsigned' => false, 'default' => '-1'))
    ->addColumn('promotion_space', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('promotion_start_page', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array('nullable' => false, 'default' => 0))
    ->addColumn('product_warranty', Varien_Db_Ddl_Table::TYPE_SMALLINT, 2, array('default' => 0, 'unsigned' => true, 'nullable' => false))
    ->addColumn('product_warranty_description_de', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => true))
    ->addColumn('product_warranty_description_fr', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => true))
    ->addColumn('product_condition', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array('nullable' => false, 'default' => 0))
    ->addColumn('product_condition_source_attribute_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => true))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->setComment('Sales options');
$installer->getConnection()->createTable($tableSalesOptions);

$tableProductListings = $installer->getConnection()->newTable($productListingTable);
$tableProductListings->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array('nullable' => false, 'default' => Diglin_Ricento_Helper_Data::STATUS_PENDING))
    ->addColumn('sales_options_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array('unsigned' => true, 'nullable' => false))
    ->addColumn('publish_languages', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => true))
    ->addColumn('default_language', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array('nullable' => true))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing', 'sales_options_id', 'diglin_ricento/sales_options', 'entity_id'),
        'sales_options_id', $salesOptionsTable, 'entity_id', Varien_Db_Ddl_Table::ACTION_NO_ACTION, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing', 'rule_id', 'diglin_ricento/shipping_payment_rule', 'rule_id'),
        'rule_id', $shippingPaymentRuleTable, 'rule_id', Varien_Db_Ddl_Table::ACTION_NO_ACTION, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
    ->setComment('List of products to be published on ricardo platform');
$installer->getConnection()->createTable($tableProductListings);

$tableProductListingItems = $installer->getConnection()->newTable($productListingItemTable);
$tableProductListingItems
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('parent_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => true, 'unsigned' => true))
    ->addColumn('ricardo_article_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('is_planned', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array('unsigned' => true, 'nullable' => true))
    ->addColumn('qty_inventory', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('unsigned' => true, 'nullable' => true))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('parent_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => true))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('sales_options_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => true, 'default' => null))
    ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => true, 'default' => null))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array('nullable' => false, 'default' => Diglin_Ricento_Helper_Data::STATUS_PENDING))
    ->addColumn('additional_data', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => true))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'products_listing_id', 'diglin_ricento/products_listing', 'entity_id'),
        'products_listing_id', $productListingTable, 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'sales_options_id', 'diglin_ricento/sales_options', 'entity_id'),
        'sales_options_id', $salesOptionsTable, 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'rule_id', 'diglin_ricento/shipping_payment_rule', 'rule_id'),
        'rule_id', $shippingPaymentRuleTable, 'rule_id', Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->setComment('Associated products for products listing');

$installer->getConnection()->createTable($tableProductListingItems);

$tablePaymentRule = $installer->getConnection()->newTable($shippingPaymentRuleTable);
$tablePaymentRule
    ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array(
        'primary' => true,
        'auto_increment' => true,
        'nullable' => false,
        'unsigned' => true
    ), 'Rule ID')
    ->addColumn('payment_methods', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Payment Methods')
    ->addColumn('payment_description_de', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true
    ), 'Payment description DE')
    ->addColumn('payment_description_fr', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true
    ), 'Payment description FR')
    ->addColumn('shipping_method', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Shipping Methods')
    ->addColumn('shipping_package', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => true
    ), 'Shipping Package')
    ->addColumn('shipping_cumulative_fee', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => 0
    ), 'Shipping Cumulative Fee')
    ->addColumn('shipping_description_de', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true
    ), 'Shipping Description DE')
    ->addColumn('shipping_description_fr', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true
    ), 'Shipping Description FR')
    ->addColumn('shipping_availability', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Shipping Availability')
    ->addColumn('shipping_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable' => true,
    ), 'Selection Price Value')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->setComment('Shipping & Payment Rule for product list or product item');
$installer->getConnection()->createTable($tablePaymentRule);

$tableSync = $installer->getConnection()->newTable($syncJobTable);
$tableSync
    ->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_message', Varien_Db_Ddl_Table::TYPE_TEXT, Varien_Db_Ddl_Table::MAX_TEXT_SIZE, array('nullable' => true))
    ->addColumn('job_status', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => true))
    ->addColumn('job_type', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => false))
    ->addColumn('started_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('ended_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->setComment('ricardo.ch synchronization job');

$installer->getConnection()->createTable($tableSync);

$installer->run("ALTER TABLE " . $tableSync->getName() . " ADD COLUMN `progress` ENUM('pending', 'ready', 'running', 'chunk_running', 'completed') NOT NULL AFTER job_type");

$tableSyncListing = $installer->getConnection()->newTable($syncJobListingTable);
$tableSyncListing
    ->addColumn('job_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('last_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true))
    ->addColumn('total_count', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'default' => 0))
    ->addColumn('total_proceed', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'default' => 0))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addForeignKey($installer->getFkName('diglin_ricento/sync_job_listing', 'job_id', 'diglin_ricento/sync_job', 'job_id'),
        'job_id', $syncJobTable, 'job_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('ricardo.ch synchronization job for listing');

$installer->getConnection()->createTable($tableSyncListing);

$tableProdListingLogs = $installer->getConnection()->newTable($listingLogTable);
$tableProdListingLogs
    ->addColumn('log_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => true, 'unsigned' => true))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('product_title', Varien_Db_Ddl_Table::TYPE_VARBINARY, 255, array('nullable' => true))
    ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, Varien_Db_Ddl_Table::MAX_TEXT_SIZE, array('nullable' => true))
    ->addColumn('log_type', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addForeignKey($installer->getFkName('diglin_ricento/listing_log', 'job_id', 'diglin_ricento/sync_job', 'job_id'),
        'job_id', $syncJobTable, 'job_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/listing_log', 'products_listing_id', 'diglin_ricento/products_listing', 'entity_id'),
        'products_listing_id', $installer->getTable('diglin_ricento/products_listing'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('diglin_ricento/listing_log', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('ricardo.ch Products Listing logs');

$installer->getConnection()->createTable($tableProdListingLogs);

$installer->run("ALTER TABLE " . $tableProdListingLogs->getName() . " ADD COLUMN `log_status` ENUM('notice', 'warning', 'error', 'success') NOT NULL AFTER log_type");

$transaction = $installer->getConnection()->newTable($transactionTable);
$transaction
    ->addColumn('transaction_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('bid_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => false))
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('address_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('language_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 3, array('unsigned' => true, 'nullable' => true))
    ->addColumn('ricardo_customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => false))
    ->addColumn('ricardo_article_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => false))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('qty', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => false))
    ->addColumn('view_count', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => true))
    ->addColumn('total_bid_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('payment_methods', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('payment_description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => true))
    ->addColumn('shipping_fee', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('shipping_method', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('shipping_text', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => true))
    ->addColumn('shipping_description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => true))
    ->addColumn('shipping_cumulative_fee', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('unsigned' => true, 'nullable' => false))
    ->addColumn('raw_data', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => false))
    ->addColumn('sold_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->setComment('Transactions done on ricardo.ch from listed articles and synced back in Magento')
    ->addForeignKey($installer->getFkName('diglin_ricento/sales_transaction', 'address_id', 'customer/address_entity', 'entity_id'),
        'address_id', $installer->getTable('customer/address_entity'), 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/sales_transaction', 'website_id', 'core/website', 'website_id'),
        'website_id', $installer->getTable('core/website'), 'website_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/sales_transaction', 'order_id', 'sales/order', 'entity_id'),
        'order_id', $installer->getTable('sales/order'), 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/sales_transaction', 'customer_id', 'customer/entity', 'entity_id'),
        'customer_id', $installer->getTable('customer/entity'), 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/sales_transaction', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL);

$installer->getConnection()->createTable($transaction);

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

// Create Catalog Category Enttities

$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Category::ENTITY);
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getAttributeGroupId($entityTypeId, $attributeSetId, 'General Information');

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'ricardo_category', array(
    'input_renderer'    => 'diglin_ricento/adminhtml_products_category_form_renderer_mapping',
    'type'              => 'int',
    'label'             => 'ricardo.ch Category',
    'note'              => 'Map this current Magento category with one of ricardo.ch. It will facilitate you the creation of product listing.',
    'input'             => 'text',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required'          => false,
    'user_defined'      => false,
    'unique'            => false,
    'default'           => ''
));

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'ricardo_category'
);

// Create Catalog Product Enttities

$ricardoGroup = 'ricardo.ch';

$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$installer->addAttributeGroup($entityTypeId, $attributeSetId, $ricardoGroup, 50);

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_title', array(
    'group'             => $ricardoGroup,
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'ricardo.ch Title',
    'note'              => 'Title is limited to 40 characters on ricardo.ch, use this field instead of the product name to prevent any unwanted behavior. Normal product name will be used if this field is empty but will be cut to 40 chars on ricardo.ch.',
    'input'             => 'text',
    'class'             => 'validate-length maximum-length-40',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'apply_to'	=> 'simple,grouped,configurable'
));

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_subtitle', array(
    'group'             => $ricardoGroup,
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'ricardo.ch Subtitle',
    'note'              => 'Subtitle is limited to 60 characters on ricardo.ch. Let empty if you don\'t need it.',
    'input'             => 'text',
    'class'             => 'validate-length maximum-length-60',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'apply_to'	=> 'simple,grouped,configurable'
));

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_description', array(
    'group'             => $ricardoGroup,
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'ricardo.ch Description',
    'note'              => '65 000 characters Max. - Description of the product for ricardo.ch page. If empty, the default Magento description of your product will be taken.',
    'input'             => 'textarea',
    'class'             => 'validate-length maximum-length-65000',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'wysiwyg_enabled' => true,
    'is_html_allowed_on_front' => true,
    'apply_to'	=> 'simple,grouped,configurable'
));

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_condition', array(
    'group'             => $ricardoGroup,
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Product Condition',
    'note'              => '',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'diglin_ricento/entity_attribute_source_conditions',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'apply_to'	=> 'simple,grouped,configurable'
));

$installer->addAttribute('customer', 'ricardo_username', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'ricardo.ch Username',
    'required' => false,
    'user_defined' => true,
    'default' => '',
    'unique' => true,
    'note' => 'ricardo.ch Username imported from ricardo.ch',
    'visible' => true,
    'visible_on_front' => false,
    'frontend_class' => 'disabled'
));

$installer->addAttribute('customer', 'ricardo_id', array(
    'type' => 'int',
    'input' => 'text',
    'label' => 'ricardo.ch Customer ID',
    'required' => false,
    'user_defined' => true,
    'default' => '',
    'unique' => true,
    'note' => 'ricardo.ch Customer ID imported from ricardo.ch',
    'visible' => true,
    'visible_on_front' => false,
    'frontend_class' => 'disabled'
));

/**
 * Add column to sales quote
 */
$installer->getConnection()->addColumn($salesQuoteTable, 'customer_ricardo_username', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'nullable' => true,
    'comment' => 'ricardo.ch Username'));

$installer->getConnection()->addColumn($salesQuoteTable, 'customer_ricardo_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'ricardo.ch ID'));

/**
 * Add column to sales quote
 */
$installer->getConnection()->addColumn($salesOrderTable, 'customer_ricardo_username', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'nullable' => true,
    'comment' => 'ricardo.ch Username'));

$installer->getConnection()->addColumn($salesOrderTable, 'customer_ricardo_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'ricardo.ch ID'));

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'ricardo_id')
    ->setData('used_in_forms', array('adminhtml_customer', 'adminhtml_checkout'))
    ->save();

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'ricardo_username')
    ->setData('used_in_forms', array('adminhtml_customer', 'adminhtml_checkout'))
    ->save();

$installer->endSetup();