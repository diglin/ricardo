<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */ 
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

// Implement sql and EAV changes from versions 0.0.1 to 0.0.7 until now (@todo finish to update here until version 1.0.0 will be officially ready to release)

$installer->startSetup();

$apiTokenTable = $installer->getTable('diglin_ricento/api_token');
$salesOptionsTable = $installer->getTable('diglin_ricento/sales_options');
$shippingPaymentRuleTable = $installer->getTable('diglin_ricento/shipping_payment_rule');
$productListingTable = $installer->getTable('diglin_ricento/products_listing');
$productListingItemTable = $installer->getTable('diglin_ricento/products_listing_item');
$syncLogTable = $installer->getTable('diglin_ricento/sync_log');

$tableApiTokens = $installer->getConnection()->newTable($apiTokenTable);
$tableApiTokens->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('token', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('token_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array('unsigned' => true, 'nullable' => false))
    ->addColumn('expiration_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable' => false))
    ->addColumn('session_duration', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false))
    ->addColumn('session_expiration_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable' => false))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->setComment('Tokens for Ricardo API');
$installer->getConnection()->createTable($tableApiTokens);

$tableSalesOptions = $installer->getConnection()->newTable($salesOptionsTable);
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
    ->addColumn('schedule_cycle_multiple_products', Varien_Db_Ddl_Table::TYPE_SMALLINT, 4, array('nullable' => true, 'default' => null))
    ->addColumn('schedule_overwrite_product_date_start', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array('unsigned' => true))
    ->addColumn('stock_management', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('default' => -1))
    ->addColumn('customization_template', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('promotion_space', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('promotion_start_page', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array('nullable' => false, 'default' => 0))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->setComment('Sales options');
$installer->getConnection()->createTable($tableSalesOptions);

$tableProductListings = $installer->getConnection()->newTable($productListingTable);
$tableProductListings->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => false))
    ->addColumn('total_active_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'default' => 0, 'nullable' => false))
    ->addColumn('total_inactive_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'default' => 0, 'nullable' => false))
    ->addColumn('total_sold_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'default' => 0, 'nullable' => false))
    ->addColumn('total_products', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'default' => 0, 'nullable' => false))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array('nullable' => false, 'default' => Diglin_Ricento_Helper_Data::STATUS_PENDING))
    ->addColumn('sales_options_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array('unsigned' => true, 'nullable' => false))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing', 'sales_options_id', 'diglin_ricento/sales_options', 'entity_id'),
        'sales_options_id', $salesOptionsTable, 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing', 'rule_id', 'diglin_ricento/shipping_payment_rule', 'rule_id'),
        'rule_id', $shippingPaymentRuleTable, 'rule_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('List of products to be published on ricardo platform');
$installer->getConnection()->createTable($tableProductListings);

$tableSyncLogs = $installer->getConnection()->newTable($syncLogTable);
$tableSyncLogs->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => false))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, 4)
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addForeignKey($installer->getFkName('diglin_ricento/sync_log', 'products_listing_id', 'diglin_ricento/products_listing', 'entity_id'),
        'products_listing_id', $productListingTable, 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Ricardo synchronization logs');
$installer->getConnection()->createTable($tableSyncLogs);

$tableProductListingItems = $installer->getConnection()->newTable($productListingItemTable);
$tableProductListingItems->addColumn('item§', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => false))
    ->addColumn('sales_options_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => true, 'default' => null))
    ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => true, 'default' => true))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array('nullable' => false, 'default' => Diglin_Ricento_Helper_Data::STATUS_PENDING))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => null))
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'products_listing_id', 'diglin_ricento/products_listing', 'entity_id'),
        'products_listing_id', $productListingTable, 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'sales_options_id', 'diglin_ricento/sales_options', 'entity_id'),
        'sales_options_id', $salesOptionsTable, 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/products_listing_item', 'rule_id', 'diglin_ricento/shipping_payment_rule', 'rule_id'),
        'rule_id', $shippingPaymentRuleTable, 'rule_id', Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addIndex($installer->getIdxName('diglin_ricento/products_listing_item', array('product_id', 'products_listing_id')),
        array('product_id', 'products_listing_id'), array('type' => 'unique'))
    ->setComment('Associated products for product listings');
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
    ->addColumn('payment_description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false
    ), 'Payment description')
    ->addColumn('shipping_method', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Shipping Method')
    ->addColumn('shipping_description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true
    ), 'Shipping Description')
    ->addColumn('shipping_availability', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Shipping Availability')
    ->addColumn('shipping_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable' => false,
        'default' => '0.0000',
    ), 'Selection Price Value')
    ->setComment('Shipping & Payment Rule for product list or product item');
$installer->getConnection()->createTable($tablePaymentRule);

$installer->endSetup();

// Create Catalog Category Enttities

$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Category::ENTITY);
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getAttributeGroupId($entityTypeId, $attributeSetId, 'General Information');

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'ricardo_category', array(
    'input_renderer'    => 'diglin_ricento/adminhtml_catalog_category_form_renderer_mapping',
    'type'              => 'int',
    'label'             => 'Ricardo Category',
    'note'              => 'Map this current Magento category with one of Ricardo. It will facilitate you the creation of product listing.',
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

$ricardoGroup = 'Ricardo';

$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$installer->addAttributeGroup($entityTypeId, $attributeSetId, $ricardoGroup, 50);

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_title', array(
    'group'             => $ricardoGroup,
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Ricardo Title',
    'note'              => 'Title is limited to 80 chars. on Ricardo.ch, use this field instead of the product name to prevent any unwanted behavior. Normal product name will be used if this field is empty. Keep in mind that the product name will be cut to 80 chars on ricardo.ch.',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
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
    'label'             => 'Ricardo Subtitle',
    'note'              => 'Title is limited to 80 chars. on Ricardo.ch',
    'input'             => 'text',
    'class'             => '',
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
    'label'             => 'Product Description',
    'note'              => 'Description of the product for Ricardo page. If empty, the default Magento description of your product will be taken.',
    'input'             => 'textarea',
    'class'             => '',
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