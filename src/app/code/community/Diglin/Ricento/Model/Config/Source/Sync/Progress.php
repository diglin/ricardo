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

/**
 * Class Diglin_Ricento_Model_Config_Source_Sync_Progress
 */
class Diglin_Ricento_Model_Config_Source_Sync_Progress extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
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