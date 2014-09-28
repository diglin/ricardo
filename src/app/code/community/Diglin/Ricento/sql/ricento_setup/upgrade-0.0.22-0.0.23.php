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