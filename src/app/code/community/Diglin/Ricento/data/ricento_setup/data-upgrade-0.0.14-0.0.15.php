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

$productsListingTable = $installer->getTable('diglin_ricento/products_listing');
$storeTable = $installer->getTable('core/store');
$languages = array('fr','de'); //Mage::helper('diglin_ricento')->getSupportedLang();

foreach ($languages as $lang) {
    if (empty($lang)) {
        continue;
    }
    $columnName = 'lang_store_id_' . $lang ;
    $installer->getConnection()->addColumn($productsListingTable, $columnName, array(
        'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable' => true,
        'after' => 'default_language',
        'comment' => 'Store View ' . strtoupper($lang)));

    $installer->getConnection()->addForeignKey(
        $installer->getFkName($productsListingTable, $columnName, $storeTable, 'store_id'),
        $productsListingTable, $columnName, $storeTable, 'store_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL
    );
}

$installer->endSetup();