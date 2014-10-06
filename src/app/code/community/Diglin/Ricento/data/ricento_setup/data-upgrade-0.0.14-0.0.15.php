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

$productsListingTable = $installer->getTable('diglin_ricento/products_listing');
$storeTable = $installer->getTable('core/store');
$languages = array('fr','de'); //Mage::helper('diglin_ricento')->getSupportedLang(); // helper not working in this context!!!

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