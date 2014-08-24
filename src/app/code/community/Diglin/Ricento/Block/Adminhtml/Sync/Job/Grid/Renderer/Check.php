<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Sync_Job_Grid_Renderer_Check extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $jobCheck = Mage::getModel('diglin_ricento/sync_job')
            ->loadByJobTypeAndProductsListingId(Diglin_Ricento_Model_Sync_Job::TYPE_CHECK, $row->getProductsListingId());

        $block = Mage::getBlockSingleton('adminhtml/template');
        $block
            ->setRunCheck(false)
            ->setTemplate('ricento/js/sync/check.phtml')
            ->setPrefix($row->getId());

        if ($jobCheck->getId() && $jobCheck->getStatus() != Diglin_Ricento_Model_Sync_Job::STATUS_COMPLETED) {
            $block
                ->setRunCheck(true)
                ->setCheckAjaxUrl($this->getUrl('ricento/sync/checkajax', array('jobid' => $jobCheck->getId())))
                ->setCheckPopupUrl($this->getUrl('ricento/sync/checkajaxpopup', array('jobid' => $jobCheck->getId())));
        }

        if ($jobCheck->getId() && $jobCheck->getStatus() == Diglin_Ricento_Model_Sync_Job::STATUS_PENDING) {
            $block->setJobWillStart(true);
        }

        return $block->toHtml();
    }
}