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

$apiTokenTable = $installer->getTable('diglin_ricento/api_token');
$productsListingTable = $installer->getTable('diglin_ricento/products_listing');

$installer->getConnection()->changeColumn($apiTokenTable, 'store_id', 'website_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'length' => 5,
    'nullable' => false,
    'comment' => 'Website ID'));

$installer->getConnection()->changeColumn($productsListingTable, 'store_id', 'website_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'length' => 5,
    'nullable' => false,
    'comment' => 'Website ID'));

$installer->endSetup();