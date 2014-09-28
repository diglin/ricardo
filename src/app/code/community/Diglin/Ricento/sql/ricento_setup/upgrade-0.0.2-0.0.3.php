<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$ricardoGroup = 'Ricardo';

$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$installer->addAttributeGroup($entityTypeId, $attributeSetId, $ricardoGroup, 50);

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_title', array(
    'group'             => $ricardoGroup,
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Ricardo Title',
    'note'              => 'Title is limited to 80 chars. on Ricardo.ch, use this field instead of the product name to prevent any unwanted behavior. Normal product name will be used if this field is empty. Keep in mind that the product name will be cut to 80 chars on ricardo.ch.',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'apply_to'	=> 'simple,grouped,configurable'
));

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_subtitle', array(
    'group'             => $ricardoGroup,
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Ricardo Subtitle',
    'note'              => 'Title is limited to 80 chars. on Ricardo.ch',
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'apply_to'	=> 'simple,grouped,configurable'
));

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_description', array(
    'group'             => $ricardoGroup,
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Ricardo Description',
    'note'              => 'Description of the product for Ricardo page. If empty, the default Magento description of your product will be taken.',
    'input'             => 'textarea',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'wysiwyg_enabled' => true,
    'is_html_allowed_on_front' => true,
    'apply_to'	=> 'simple,grouped,configurable'
));

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'ricardo_condition', array(
    'group'             => $ricardoGroup,
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Product Condition',
    'note'              => '',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'diglin_ricento/entity_attribute_source_conditions',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'apply_to'	=> 'simple,grouped,configurable'
));

$installer->startSetup();

$installer->getConnection()->changeColumn(
    $installer->getTable('diglin_ricento/products_listing_item'), 'entity_id', 'item_id',
    array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER, 'primary' => true, 'auto_increment' => true, 'nullable' => false, 'unsigned' => true));

$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/api_token'), 'store_id',
    array('type' => Varien_Db_Ddl_Table::TYPE_SMALLINT, 'nullable' => false, 'unsigned' => true));

$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/products_listing'), 'store_id',
    array('type' => Varien_Db_Ddl_Table::TYPE_SMALLINT, 'nullable' => false, 'unsigned' => true));

$installer->getConnection()->modifyColumn(
    $installer->getTable('diglin_ricento/sales_options'), 'schedule_cycle_multiple_products',
    array('type' => Varien_Db_Ddl_Table::TYPE_SMALLINT, 'nullable' => true, 'default' => null));

$installer->endSetup();
