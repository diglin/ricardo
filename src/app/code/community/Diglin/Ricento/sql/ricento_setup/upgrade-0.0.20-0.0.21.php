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

$tableSync = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/sync_job'));
$tableSync->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_message', Varien_Db_Ddl_Table::TYPE_TEXT, Varien_Db_Ddl_Table::MAX_TEXT_SIZE, array('nullable' => true))
    ->addColumn('job_status', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => true))
    ->addColumn('started_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('ended_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->setComment('Ricardo synchronization job');

$installer->getConnection()->createTable($tableSync);

$installer->run("ALTER TABLE " . $tableSync->getName() . " ADD COLUMN `job_type` ENUM('check_list', 'list', 'stop', 'update') NOT NULL AFTER job_status");
$installer->run("ALTER TABLE " . $tableSync->getName() . " ADD COLUMN `progress` ENUM('pending', 'ready', 'running', 'completed') NOT NULL AFTER job_type");

$tableSyncListing = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/sync_job_listing'));
$tableSyncListing
    ->addColumn('job_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('last_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true))
    ->addColumn('total_count', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'default' => 0))
    ->addColumn('total_proceed', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'default' => 0))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->addForeignKey($installer->getFkName('diglin_ricento/sync_job_listing', 'job_id', 'diglin_ricento/sync_job', 'job_id'),
        'job_id', $installer->getTable('diglin_ricento/sync_job'), 'job_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Ricardo synchronization job for listing');

$installer->getConnection()->createTable($tableSyncListing);

$tableProdListingLogs = $installer->getConnection()->newTable($installer->getTable('diglin_ricento/listing_log'));
$tableProdListingLogs
    ->addColumn('log_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('job_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => true, 'unsigned' => true))
    ->addColumn('products_listing_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('product_title', Varien_Db_Ddl_Table::TYPE_VARBINARY, 255, array('nullable' => true))
    ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, Varien_Db_Ddl_Table::MAX_TEXT_SIZE, array('nullable' => true))
    ->addColumn('log_type', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('nullable' => false, 'unsigned' => true))
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->addForeignKey($installer->getFkName('diglin_ricento/listing_log', 'job_id', 'diglin_ricento/sync_job', 'job_id'),
        'job_id', $installer->getTable('diglin_ricento/sync_job'), 'job_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/listing_log', 'products_listing_id', 'diglin_ricento/products_listing', 'entity_id'),
        'products_listing_id', $installer->getTable('diglin_ricento/products_listing'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('diglin_ricento/listing_log', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Ricardo Products Listing logs');

$installer->getConnection()->createTable($tableProdListingLogs);

$installer->run("ALTER TABLE " . $tableProdListingLogs->getName() . " ADD COLUMN `log_status` ENUM('notice', 'warning', 'error', 'success') NOT NULL AFTER log_type");

//$installer->getConnection()->dropTable('diglin_ricento/sync_log');

$installer->endSetup();