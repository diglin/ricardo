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

$itemTable = $installer->getTable('diglin_ricento/products_listing_item');
$listTable = $installer->getTable('diglin_ricento/products_listing');
$salesOptionsTable = $installer->getTable('diglin_ricento/sales_options');
$shippingPaymentTable = $installer->getTable('diglin_ricento/shipping_payment_rule');

// FK changes for product listing item table
$installer->getConnection()->addForeignKey(
    $installer->getFkName($itemTable, 'sales_options_id', $salesOptionsTable, 'entity_id'),
    $itemTable, 'sales_options_id', $salesOptionsTable, 'entity_id',
    Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName($itemTable, 'rule_id', $shippingPaymentTable, 'rule_id'),
    $itemTable, 'rule_id', $shippingPaymentTable, 'rule_id',
    Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL
);

// FK changes for product listing table
$installer->getConnection()->addForeignKey(
    $installer->getFkName($listTable, 'sales_options_id', $salesOptionsTable, 'entity_id'),
    $listTable, 'sales_options_id', $salesOptionsTable, 'entity_id',
    Varien_Db_Ddl_Table::ACTION_NO_ACTION, Varien_Db_Ddl_Table::ACTION_NO_ACTION
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName($listTable, 'rule_id', $shippingPaymentTable, 'rule_id'),
    $listTable, 'rule_id', $shippingPaymentTable, 'rule_id',
    Varien_Db_Ddl_Table::ACTION_NO_ACTION, Varien_Db_Ddl_Table::ACTION_NO_ACTION
);

$installer->endSetup();