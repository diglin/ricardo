<?php

/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Config_Source_Sync_Status extends Diglin_Ricento_Model_Config_Source_Abstract
{

    public function toOptionHash()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            Diglin_Ricento_Model_Sync_Job::STATUS_PENDING => $helper->__('Idle'),
            Diglin_Ricento_Model_Sync_Job::STATUS_RUNNING => $helper->__('Running Now'),
            Diglin_Ricento_Model_Sync_Job::STATUS_CHUNK_RUNNING => $helper->__('Running'),
            Diglin_Ricento_Model_Sync_Job::STATUS_COMPLETED => $helper->__('Completed')
        );
    }

}