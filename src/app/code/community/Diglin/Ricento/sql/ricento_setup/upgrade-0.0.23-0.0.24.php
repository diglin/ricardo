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

$shippingPaymentRuleTable = $installer->getTable('diglin_ricento/shipping_payment_rule');

$installer->getConnection()->changeColumn($shippingPaymentRuleTable, 'shipping_description', 'shipping_description_de', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'after' => 'shipping_cumulative_fee',
    'comment' => 'Shipping Description DE'));

$installer->getConnection()->addColumn($shippingPaymentRuleTable, 'shipping_description_fr', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'after' => 'shipping_cumulative_fee',
    'comment' => 'Shipping Description FR'));

$installer->endSetup();