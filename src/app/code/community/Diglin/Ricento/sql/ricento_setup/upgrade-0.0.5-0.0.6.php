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

$tables = array(
    $installer->getTable('diglin_ricento/products_listing_item'),
    $installer->getTable('diglin_ricento/products_listing'),
    $installer->getTable('diglin_ricento/sales_options'),
    $installer->getTable('diglin_ricento/sync_log'),
);

foreach ($tables as $table) {
    $installer->getConnection()->modifyColumn(
        $table, 'created_at',
        array('type' => Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 'nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT));
    $installer->getConnection()->modifyColumn(
        $table, 'updated_at',
        array('type' => Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 'nullable' => true, 'default' => null));
}
$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/api_token'), 'created_at',
    array('type' => Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 'nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT));

$installer->endSetup();