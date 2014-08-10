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

$apiTokenTable = $installer->getTable('diglin_ricento/api_token');

$installer->getConnection()->addColumn($apiTokenTable, 'updated_at', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'nullable' => true,
    'default' => null,
    'comment' => 'Updated At'));

$installer->endSetup();