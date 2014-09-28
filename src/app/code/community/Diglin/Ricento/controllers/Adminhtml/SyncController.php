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
            $dateFormatIso = Mage::helper('diglin_ricento')->getDateTimeIsoFormat();

            $jobMessage = $job->getJobMessage();

            if (is_array($jobMessage)) {
                $jobMessage = implode('<br>', $jobMessage);
            }

            $response = array(
                'percentage' => $percentDone,
                'status' => ucfirst($job->getJobStatus()),
                'state' => $job->getProgress(),
                'message' => Mage::getSingleton('diglin_ricento/filter')->filter($jobMessage),
                'started_at' => ($job->getStartedAt()) ? $locale->date($job->getStartedAt())->toString($dateFormatIso) : '',
                'ended_at' => ($job->getEndedAt()) ? $locale->date($job->getEndedAt())->toString($dateFormatIso) : ''
            );
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }
}