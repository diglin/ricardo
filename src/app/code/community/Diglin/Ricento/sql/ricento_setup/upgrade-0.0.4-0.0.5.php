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

$itemTable = $installer->getTable('diglin_ricento/products_listing_item');
$salesOptionsTable = $installer->getTable('diglin_ricento/sales_options');

$installer->getConnection()->modifyColumn(
    $itemTable, 'sales_options_id',
    array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER, 'nullable' => true, 'default' => null, 'unsigned' => true));

$installer->run("UPDATE {$itemTable} SET sales_options_id=NULL WHERE sales_options_id = 0");

$installer->getConnection()->addForeignKey(
    $installer->getFkName($itemTable, 'sales_options_id', $salesOptionsTable, 'entity_id'),
    $itemTable, 'sales_options_id', $salesOptionsTable, 'entity_id',
    Varien_Db_Ddl_Table::ACTION_SET_NULL
);

$installer->endSetup();