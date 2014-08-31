<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Model_Config_Source_Sync_Progress extends Diglin_Ricento_Model_Config_Source_Abstract
{
    public function toOptionHash()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING => $helper->__('Pending'),
            Diglin_Ricento_Model_Sync_Job::PROGRESS_RUNNING => $helper->__('Running'),
            Diglin_Ricento_Model_Sync_Job::PROGRESS_READY => $helper->__('Ready'),
            Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED => $helper->__('Completed')
        );
    }
}