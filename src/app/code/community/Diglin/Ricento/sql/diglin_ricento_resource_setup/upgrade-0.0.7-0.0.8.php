<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$shippingPaymentRuleTable = $installer->getTable('diglin_ricento/shipping_payment_rule');
$productListingItemTable = $installer->getTable('diglin_ricento/products_listing_item');
$productListingTable = $installer->getTable('diglin_ricento/products_listing');

// Create Ricento Shipping Payment Rule Table and Foreign Keys

$tablePaymentRule = $installer->getConnection()->newTable($shippingPaymentRuleTable);
$tablePaymentRule->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array(
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
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created at')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        'default' => null
    ), 'Updated at')
    ->setComment('Shipping & Payment Rule for product list or product item');
$installer->getConnection()->createTable($tablePaymentRule);

$installer->getConnection()->addColumn($productListingItemTable, 'rule_id', array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER, 'nullable' => true, 'unsigned' => true, 'comment' => 'Shipping Payment Rule ID'));
$installer->getConnection()->addColumn($productListingTable, 'rule_id', array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER, 'nullable' => false, 'unsigned' => true, 'comment' => 'Shipping Payment Rule ID'));

$installer->getConnection()->addForeignKey(
    $installer->getFkName($productListingItemTable, 'rule_id', $shippingPaymentRuleTable, 'rule_id'),
    $productListingItemTable, 'rule_id', $shippingPaymentRuleTable, 'rule_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName($productListingTable, 'rule_id', $shippingPaymentRuleTable, 'rule_id'),
    $productListingTable, 'rule_id', $shippingPaymentRuleTable, 'rule_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE
);

$installer->endSetup();