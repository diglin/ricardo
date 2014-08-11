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

$salesOptions = $installer->getTable('diglin_ricento/sales_options');

$installer->getConnection()->changeColumn($salesOptions, 'customization_template', 'customization_template', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length' => 10,
    'nullable' => true,
    'default' => '-1',
    'comment' => 'Custom Template Id'));

$installer->endSetup();