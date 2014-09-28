<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$salesOptionsTable = $installer->getTable('diglin_ricento/sales_options');

$installer->getConnection()->changeColumn($salesOptionsTable, 'price_source_attribute_id', 'price_source_attribute_code', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'nullable' => true,
    'comment' => 'Price Attribute Code'));

$installer->endSetup();