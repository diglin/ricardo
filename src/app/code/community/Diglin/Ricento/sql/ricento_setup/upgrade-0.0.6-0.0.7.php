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

// It's a wanted behavior to use renameTable method to not have as a first parameter $installer->getTable('XYZ/XYZ').
// Errors were found in the file install-0.0.1.php and previous developers did tests on DB w/o table prefix which makes install error on DB with tables prefix
// This script is targeted only for them, in other cases this code will send exception and logic will be skipped

try {
    // change table name from ricento_product_listings to ricento_product_listing
    $installer->getConnection()->renameTable('ricento_product_listings', $installer->getTable('diglin_ricento/products_listing'));
} catch (Exception $e) {
    // Exceptions may be raised in case of the table doesn't exist or already exists. In any case we do not care.
    Mage::logException($e);
}

try {
    // change table name from ricento_api_tokens to ricento_api_token
    $installer->getConnection()->renameTable('ricento_api_tokens', $installer->getTable('diglin_ricento/api_token'));
} catch (Exception $e) {
    // Exceptions may be raised in case of the table doesn't exist or already exists. In any case we do not care.
    Mage::logException($e);
}

try {
    // change table name from ricento_sync_logs to ricento_sync_log
    $installer->getConnection()->renameTable('ricento_sync_logs', $installer->getTable('diglin_ricento/sync_log'));
} catch (Exception $e) {
    // Exceptions may be raised in case of the table doesn't exist or already exists. In any case we do not care.
    Mage::logException($e);
}

try {
    // change table name from ricento_products_listing_items to ricento_product_listing_item
    $installer->getConnection()->renameTable('ricento_products_listing_items', $installer->getTable('diglin_ricento/products_listing_item'));
} catch (Exception $e) {
    // Exceptions may be raised in case of the table doesn't exist or already exists. In any case we do not care.
    Mage::logException($e);
}

$installer->endSetup();