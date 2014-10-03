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
class Diglin_Ricento_Model_Config_Source_Sync_Type extends Diglin_Ricento_Model_Config_Source_Abstract
{
    public function toOptionHash()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST => $helper->__('Products Check Job'),
            Diglin_Ricento_Model_Sync_Job::TYPE_LIST => $helper->__('List Job'),
            Diglin_Ricento_Model_Sync_Job::TYPE_STOP => $helper->__('Stop List Job'),
            Diglin_Ricento_Model_Sync_Job::TYPE_ORDER => $helper->__('Sync Order Job'),
            Diglin_Ricento_Model_Sync_Job::TYPE_RELIST => $helper->__('Relist Job'),
            Diglin_Ricento_Model_Sync_Job::TYPE_UPDATE => $helper->__('Update Job'),
            Diglin_Ricento_Model_Sync_Job::TYPE_SYNCLIST => $helper->__('Sync List Job'),
        );
    }
}