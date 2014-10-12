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

$installer->run("ALTER TABLE " . $installer->getTable('diglin_ricento/sync_job') . " CHANGE `progress` `progress` ENUM('pending', 'ready', 'running', 'chunk_running', 'completed') NOT NULL AFTER job_type");
$installer->run("ALTER TABLE " . $installer->getTable('diglin_ricento/sync_job') . " CHANGE `job_type` `job_type` ENUM('check_list', 'list', 'relist', 'stop', 'update') NOT NULL AFTER job_status");

$installer->endSetup();