<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Adminhtml_SyncController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Ajax call to get the status of a running job
     */
    public function jobMessagePopupAction()
    {
        $jobId = (int)$this->getRequest()->getParam('jobid');

        /* @var $job Diglin_Ricento_Model_Sync_Job */
        $job = Mage::getModel('diglin_ricento/sync_job')->load($jobId);

        $this->loadLayout();

        $block = $this->getLayout()->getBlock('sync_ajax_popup');

        if ($job->getId() && $block) {
            $block->setMessages($job->getJobMessage());
            $block->setProductsListingId($job->getProductsListingId());
        }

        $this->renderLayout();
    }

    /**
     * Ajax call to get the status of a running job
     */
    public function progressAjaxAction()
    {
        $jobId = (int) $this->getRequest()->getParam('jobid');
        $isAjax = (bool) ($this->getRequest()->getQuery('isAjax', false) || $this->getRequest()->getQuery('ajax', false));
        $percentDone = 0;
        $response = array();

        if (!$isAjax) {
            $this->_getSession()->addError($this->__('Only Ajax call is allowed here.'));
            $this->_redirect('*/*/index');
            return;
        }

        /* @var $job Diglin_Ricento_Model_Sync_Job */
        $job = Mage::getModel('diglin_ricento/sync_job')->load($jobId);

        if ($job->getId()) {

            $jobListing = Mage::getModel('diglin_ricento/sync_job_listing')->load($jobId, 'job_id');
            $totalProceed = $jobListing->getTotalProceed();
            $totalCount = $jobListing->getTotalCount();

            if ($totalCount) {
                $percentDone = ($totalProceed * 100) / $totalCount;
            }

            $locale = Mage::app()->getLocale();
            $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
                Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
            );

            $response = array(
                'percentage' => $percentDone,
                'status' => ucfirst($job->getJobStatus()),
                'state' => $job->getProgress(),
                'message' => $job->getJobMessage(),
                'started_at' => ($job->getStartedAt()) ? $locale->date($job->getStartedAt())->toString($dateFormatIso) : '',
                'ended_at' => ($job->getEndedAt()) ? $locale->date($job->getEndedAt())->toString($dateFormatIso) : ''
            );
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }
}