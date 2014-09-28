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

$productsListingTable = $installer->getTable('diglin_ricento/products_listing');
$storeTable = $installer->getTable('core/store');
$languages = Mage::helper('diglin_ricento')->getSupportedLang();

$installer->getConnection()->addColumn($productsListingTable, 'publish_languages', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'nullable' => true,
    'after' => 'website_id',
    'comment' => 'Which languages to publish on ricardo.ch'));

$installer->getConnection()->addColumn($productsListingTable, 'default_language', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 50,
    'nullable' => true,
    'after' => 'publish_languages',
    'comment' => 'Which default language to publish on ricardo.ch'));

$installer->endSetup();