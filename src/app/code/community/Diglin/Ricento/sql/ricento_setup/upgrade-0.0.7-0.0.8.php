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
        'nullable' => true
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
        'nullable' => true,
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
    Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName($productListingTable, 'rule_id', $shippingPaymentRuleTable, 'rule_id'),
    $productListingTable, 'rule_id', $shippingPaymentRuleTable, 'rule_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE
);

$installer->endSetup();