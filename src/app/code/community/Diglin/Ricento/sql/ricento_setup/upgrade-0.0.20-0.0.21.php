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

$tableSync = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/sync_job'));
$tableSync->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => true))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('last_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true))
    ->addColumn('total_count', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'default' => 0))
    ->addColumn('total_proceed', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'default' => 0))
    ->addColumn('started_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addForeignKey($installer->getFkName('diglin_ricento/sync_job', 'products_listing_id', 'diglin_ricento/products_listing', 'entity_id'),
        'products_listing_id', $installer->getTable('diglin_ricento/products_listing'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Ricardo synchronization job');

$installer->getConnection()->createTable($tableSync);

$installer->run("ALTER TABLE " . $tableSync->getName() . " ADD COLUMN `job_type` ENUM('check', 'sync') NOT NULL");
$installer->run("ALTER TABLE " . $tableSync->getName() . " ADD COLUMN `status` ENUM('pending', 'running', 'chunk_running', 'completed') NOT NULL");

$installer->endSetup();