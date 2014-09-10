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

$installer->run("ALTER TABLE " . $installer->getTable('diglin_ricento/sync_job') . " CHANGE `progress` `progress` ENUM('pending', 'ready', 'running', 'chunk_running', 'completed') NOT NULL AFTER job_type");

$installer->endSetup();