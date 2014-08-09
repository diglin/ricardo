<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/products_listing'), 'status',
    array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'length' => 20, 'nullable' => false, 'default' => Diglin_Ricento_Helper_Data::STATUS_PENDING));
$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/products_listing_item'), 'status',
    array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'length' => 20, 'nullable' => false, 'default' => Diglin_Ricento_Helper_Data::STATUS_PENDING));

$installer->endSetup();