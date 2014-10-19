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
 * Add a column to products listing item table
 */
$itemTable = $installer->getTable('diglin_ricento/products_listing_item');

$installer->getConnection()->addColumn($itemTable, 'parent_product_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length' => 4,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'Parent Product Id'));

$installer->getConnection()->addColumn($itemTable, 'parent_item_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length' => 4,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'Parent Item Id'));

$installer->getConnection()->addColumn($itemTable, 'additional_data', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'Additional Data'));

$installer->endSetup();