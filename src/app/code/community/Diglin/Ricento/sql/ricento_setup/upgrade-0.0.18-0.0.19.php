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