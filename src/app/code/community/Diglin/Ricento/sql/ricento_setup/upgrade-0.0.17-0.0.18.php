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

$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_title', 'frontend_class', 'validate-length maximum-length-40');
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_title', 'note', 'Title is limited to 40 characters on Ricardo.ch, use this field instead of the product name to prevent any unwanted behavior. Normal product name will be used if this field is empty but will be cut to 40 chars on ricardo.ch.');
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_subtitle', 'frontend_class', 'validate-length maximum-length-60');
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_subtitle', 'note', 'Subtitle is limited to 60 characters on Ricardo.ch. Let empty if you don\'t need it.');
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_description', 'frontend_class', 'validate-length maximum-length-65000');
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_description', 'note', '65 000 characters Max.');

$installer->endSetup();