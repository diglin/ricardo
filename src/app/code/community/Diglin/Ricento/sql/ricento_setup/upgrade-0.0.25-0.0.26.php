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

$itemTable = $installer->getTable('diglin_ricento/products_listing_item');

$installer->getConnection()->addColumn($itemTable, 'qty_inventory', array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'nullable' => true,
    'unsigned' => true,
    'after' => 'ricardo_article_id',
    'comment' => 'Qty sync into ricardo.ch'));

$installer->endSetup();