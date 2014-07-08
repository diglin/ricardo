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

$installer->getConnection()->changeColumn(
    $installer->getTable('diglin_ricento/products_listing_item'), 'entity_id', 'item_id',
        array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER, 'primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true));

$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/api_token'), 'store_id',
        array('type' => Varien_Db_Ddl_Table::TYPE_SMALLINT, 'nullable' => false, 'unsigned' => true));

$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/products_listing'), 'store_id',
        array('type' => Varien_Db_Ddl_Table::TYPE_SMALLINT, 'nullable' => false, 'unsigned' => true));

$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/sales_options'), 'schedule_cycle_multiple_products',
        array('type' => Varien_Db_Ddl_Table::TYPE_SMALLINT, 'nullable' => true, 'default' => null));

$installer->endSetup();