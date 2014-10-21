<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

// Customer Attributes
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_id');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_username');

// Product Attributes
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_category');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_title');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_subtitle');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_description');
$installer->deleteTableRow($installer->getTable('eav_attribute'), 'attribute_code', 'ricardo_condition');

// Sales Quote Columns
$installer->getConnection()->dropColumn($installer->getTable('sales/quote'), 'is_ricardo');
$installer->getConnection()->dropColumn($installer->getTable('sales/quote'), 'customer_ricardo_id');
$installer->getConnection()->dropColumn($installer->getTable('sales/quote'), 'customer_ricardo_username');

// Sales Order Columns
$installer->getConnection()->dropColumn($installer->getTable('sales/order'), 'is_ricardo');
$installer->getConnection()->dropColumn($installer->getTable('sales/order'), 'customer_ricardo_id');
$installer->getConnection()->dropColumn($installer->getTable('sales/order'), 'customer_ricardo_username');

// Remove all ricento tables
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/api_token'));
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/products_listing'));
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/products_listing_item'));
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/listing_log'));
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/sales_options'));
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/shipping_payment_rule'));
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/sync_job'));
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/sales_transaction'));
$installer->getConnection()->dropTable($installer->getTable('diglin_ricento/sync_job_listing'));