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

$installer->addAttribute('customer', 'ricardo_username', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Ricardo Username',
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'unique' => true,
    'note' => 'Ricardo Username imported after an order creation',
    'visible' => true,
    'visible_on_front' => false,
    'frontend_class' => 'disabled'
));

$installer->addAttribute('customer', 'ricardo_customer_id', array(
    'type' => 'int',
    'input' => 'text',
    'label' => 'Ricardo Customer ID',
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'unique' => true,
    'note' => 'Ricardo Customer ID imported after an order creation',
    'visible' => true,
    'visible_on_front' => false,
    'frontend_class' => 'disabled'
));

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'ricardo_username')
    ->setData('used_in_forms', array('adminhtml_customer'))
    ->save();

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'ricardo_customer_id')
    ->setData('used_in_forms', array('adminhtml_customer'))
    ->save();

$transactionTable = $installer->getTable('diglin_ricento/sales_transaction');
$transaction = $installer->getConnection()->newTable($transactionTable);
$transaction
    ->addColumn('transaction_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true))
    ->addColumn('bid_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('address_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('ricardo_customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => false))
    ->addColumn('ricardo_article_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => false))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('qty', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => true))
    ->addColumn('view_count', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array('unsigned' => true, 'nullable' => true))
    ->addColumn('total_bid_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('payment_method', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => true))
    ->addColumn('shipping_fee', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false, 'unsigned' => true, 'default' => 0))
    ->addColumn('shipping_method', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array('nullable' => true))
    ->addColumn('shipping_cumulative_fee', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array('unsigned' => true, 'nullable' => true))
    ->addColumn('raw_data', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => true))
    ->addColumn('sold_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP)
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array('nullable' => true, 'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT))
    ->setComment('Transactions done on ricardo.ch from listed articles and synced back in Magento')
    ->addForeignKey($installer->getFkName('diglin_ricento/sales_transaction', 'order_id', 'sales/order', 'entity_id'),
        'order_id', $installer->getTable('sales/order'), 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/sales_transaction', 'customer_id', 'customer/entity', 'entity_id'),
        'customer_id', $installer->getTable('customer/entity'), 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('diglin_ricento/sales_transaction', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL);

$installer->getConnection()->createTable($transaction);
