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

$shippingPaymentRule = $installer->getTable('diglin_ricento/shipping_payment_rule');

$installer->getConnection()->addColumn($shippingPaymentRule, 'shipping_package', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'nullable' => true,
    'after' => 'shipping_method',
    'comment' => 'Shipping Package'));

$installer->getConnection()->addColumn($shippingPaymentRule, 'shipping_cumulative_fee', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'nullable' => false,
    'default' => 0,
    'after' => 'shipping_package',
    'comment' => 'Shipping Cumulative Fee'));

$installer->endSetup();