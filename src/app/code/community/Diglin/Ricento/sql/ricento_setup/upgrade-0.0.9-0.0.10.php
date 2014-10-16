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

$salesOptionsTable = $installer->getTable('diglin_ricento/sales_options');

$installer->getConnection()->addColumn($salesOptionsTable, 'product_warranty', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'nullable' => false,
    'unsigned' => true,
    'default' => 0,
    'after' => 'promotion_start_page',
    'comment' => 'Product Warranty'));

$installer->getConnection()->addColumn($salesOptionsTable, 'product_condition', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 50,
    'nullable' => true,
    'after' => 'product_warranty',
    'comment' => 'Product Condition'));

$installer->getConnection()->addColumn($salesOptionsTable, 'product_condition_source_attribute_code', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'nullable' => true,
    'after' => 'product_condition',
    'comment' => 'Product Condition Attribute Code'));

$installer->endSetup();