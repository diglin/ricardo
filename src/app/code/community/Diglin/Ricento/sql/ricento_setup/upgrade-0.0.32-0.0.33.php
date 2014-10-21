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

/**
 * Add a column to sales/quote table
 */
$salesQuoteTable = $installer->getTable('sales/quote');
$salesOrderTable = $installer->getTable('sales/order');

/**
 * Add column to sales quote
 */
$installer->getConnection()->addColumn($salesQuoteTable, 'customer_ricardo_username', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'nullable' => true,
    'comment' => 'Ricardo Username'));

$installer->getConnection()->addColumn($salesQuoteTable, 'customer_ricardo_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'Ricardo ID'));

/**
 * Add column to sales quote
 */
$installer->getConnection()->addColumn($salesOrderTable, 'customer_ricardo_username', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'nullable' => true,
    'comment' => 'Ricardo Username'));

$installer->getConnection()->addColumn($salesOrderTable, 'customer_ricardo_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable' => true,
    'unsigned' => true,
    'comment' => 'Ricardo ID'));

// We do not care of existing data, it's a DEV version touching a restricted number of person
$installer->removeAttribute('customer', 'ricardo_customer_id');

$installer->addAttribute('customer', 'ricardo_id', array(
    'type' => 'int',
    'input' => 'text',
    'label' => 'Ricardo Customer ID',
    'required' => false,
    'user_defined' => true,
    'default' => '',
    'unique' => true,
    'note' => 'Ricardo Customer ID imported from ricardo.ch',
    'visible' => true,
    'visible_on_front' => false,
    'frontend_class' => 'disabled'
));

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'ricardo_id')
    ->setData('used_in_forms', array('adminhtml_customer', 'adminhtml_checkout'))
    ->save();

$installer->updateAttribute('customer', 'ricardo_username', 'is_user_defined', true);

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'ricardo_username')
    ->setData('used_in_forms', array('adminhtml_customer', 'adminhtml_checkout'))
    ->save();

$installer->endSetup();