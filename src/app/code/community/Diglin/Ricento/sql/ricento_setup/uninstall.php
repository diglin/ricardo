<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

// Customer Attributes
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_customer_id');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_username');

// Product Attributes
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_category');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_title');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_subtitle');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_description');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_condition');

$installer->getConnection()->dropColumn($installer->getTable('sales/quote'), 'is_ricardo');
$installer->getConnection()->dropColumn($installer->getTable('sales/order'), 'is_ricardo');

$installer->getConnection()->dropColumn($installer->getTable('sales/quote_item'), 'ricardo_transaction_id');
$installer->getConnection()->dropColumn($installer->getTable('sales/order_item'), 'ricardo_transaction_id');