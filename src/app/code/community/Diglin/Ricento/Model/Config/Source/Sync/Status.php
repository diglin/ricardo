<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Sync_Status
 */
class Diglin_Ricento_Model_Config_Source_Sync_Status extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            Diglin_Ricento_Model_Sync_Job::STATUS_NOTICE => $helper->__('Notice'),
            Diglin_Ricento_Model_Sync_Job::STATUS_SUCCESS => $helper->__('Success'),
            Diglin_Ricento_Model_Sync_Job::STATUS_ERROR => $helper->__('Error'),
            Diglin_Ricento_Model_Sync_Job::STATUS_WARNING => $helper->__('Warning')
        );
    }
}