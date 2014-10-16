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

$connection = $installer->getConnection();
$data = array(
    array('ricardo_payment_canceled', 'Ricardo Payment Canceled'),
    array('ricardo_payment_pending', 'Pending Ricardo Payment')
);
$connection = $installer->getConnection()->insertArray(
    $installer->getTable('sales/order_status'),
    array('status', 'label'),
    $data
);

$relation = array(
    array('canceled', 'ricardo_payment_canceled', 0),
    array('pending', 'ricardo_payment_pending', 0),
);

$connection = $installer->getConnection()->insertArray(
    $installer->getTable('sales/order_status_state'),
    array('state', 'status', 'is_default'),
    $relation
);

$installer->endSetup();