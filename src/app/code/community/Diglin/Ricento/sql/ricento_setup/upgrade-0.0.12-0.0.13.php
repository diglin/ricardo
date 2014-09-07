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

$salesOptionsTable = $installer->getTable('diglin_ricento/sales_options');

$installer->getConnection()->addColumn($salesOptionsTable, 'product_warranty_description', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'after' => 'product_warranty',
    'comment' => 'Product Warranty Description'));

$installer->endSetup();