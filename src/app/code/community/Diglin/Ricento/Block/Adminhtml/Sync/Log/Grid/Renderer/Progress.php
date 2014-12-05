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
class Diglin_Ricento_Block_Adminhtml_Sync_Log_Grid_Renderer_Progress extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        /* @var $jobProgress Diglin_Ricento_Model_Sync_Job */
        $jobProgress = Mage::getModel('diglin_ricento/sync_job')->load($row->getId());

        $block = Mage::getBlockSingleton('adminhtml/template');
        $block
            ->setRunProgress(false)
            ->setTemplate('ricento/js/sync/progress.phtml')
            ->setProgressPopupUrl($this->getUrl('ricento/sync/jobMessagePopup', array('jobid' => $row->getId())))
            ->setProgressAjaxUrl($this->getUrl('ricento/sync/progressAjax', array('jobid' => $row->getId())))
            ->setPrefix($row->getId());

        if ($row->getId()) {
            switch($jobProgress->getProgress()) {
                case Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED:
                    break;
                case Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING:
                    $block->setJobWillStart(true);
                    // keep no break
                default:
                    $block->setRunProgress(true);
                    break;
            }
        }

        if ($jobProgress->getJobType() == Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST
            && $jobProgress->getProgress() == Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING) {
            // Run the check list without using Cron
            $html = '<script>new Ajax.Request(\''. $this->getUrl('ricento/products_listing/checkAjax') .'\');</script>';
            $block->setAdditionalHtml($html);
        }

        return $block->toHtml();
    }
}