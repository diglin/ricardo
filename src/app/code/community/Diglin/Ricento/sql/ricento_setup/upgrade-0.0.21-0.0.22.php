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

$salesOptionsTable = $installer->getTable('diglin_ricento/sales_options');
$shippingPaymentRuleTable = $installer->getTable('diglin_ricento/shipping_payment_rule');

$installer->getConnection()->changeColumn($salesOptionsTable, 'product_warranty_description', 'product_warranty_description_de', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'after' => 'product_warranty',
    'comment' => 'Product Warranty Description DE'));

$installer->getConnection()->addColumn($salesOptionsTable, 'product_warranty_description_fr', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'after' => 'product_warranty',
    'comment' => 'Product Warranty Description FR'));

$installer->getConnection()->changeColumn($shippingPaymentRuleTable, 'payment_description', 'payment_description_de', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'after' => 'payment_methods',
    'comment' => 'Product Warranty Description DE'));

$installer->getConnection()->addColumn($shippingPaymentRuleTable, 'payment_description_fr', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'after' => 'payment_methods',
    'comment' => 'Payment Description FR'));

$installer->endSetup();