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

$itemTable = $installer->getTable('diglin_ricento/products_listing_item');

$installer->getConnection()->addColumn($itemTable, 'ricardo_article_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable' => true,
    'unsigned' => true,
    'after' => 'product_id',
    'comment' => 'Ricardo Article Id'));

$installer->getConnection()->addColumn($itemTable, 'is_planned', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'nullable' => true,
    'unsigned' => true,
    'after' => 'ricardo_article_id',
    'comment' => 'Is Planned'));

$installer->endSetup();