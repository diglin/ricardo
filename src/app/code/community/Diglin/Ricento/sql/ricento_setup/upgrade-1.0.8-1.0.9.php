<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 */
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$syncJobtable = $installer->getTable('diglin_ricento/sync_job_listing');

$installer->getConnection()->addColumn($syncJobtable, 'total_success', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length' => 10,
    'nullable' => false,
    'unsigned' => true,
    'default' => 0,
    'after' => 'total_count',
    'comment' => 'Total Success'));

$installer->getConnection()->addColumn($syncJobtable, 'total_error', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length' => 10,
    'nullable' => false,
    'unsigned' => true,
    'default' => 0,
    'after' => 'total_success',
    'comment' => 'Total Error'));

$installer->endSetup();