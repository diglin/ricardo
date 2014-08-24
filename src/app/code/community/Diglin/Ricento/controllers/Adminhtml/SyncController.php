<?php

/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Adminhtml_SyncController extends Diglin_Ricento_Controller_Adminhtml_Action
{
    /**
     * Show the grid of sync in progress or done
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Start the job to check a products listing before to be synced
     */
    public function checkAction()
    {
        $productListing = $this->_initListing();

        if ($productListing) {

            $totalItems = Mage::getResourceModel('diglin_ricento/products_listing_item')
                ->countNotListedItems($productListing->getId());

            if ($totalItems === 0) {
                $this->_getSession()->addError($this->__('There is no product to check. Please, add products to your products listing.'));
                $this->_redirect('*/products_listing/edit', array('id' => $productListing->getId()));
                return;
            }

            // Create a job to prepare the sync to Ricardo.ch

            $job = Mage::getModel('diglin_ricento/sync_job');
            if (!$job->loadByJobTypeAndProductsListingId(Diglin_Ricento_Model_Sync_Job::TYPE_SYNC, $productListing->getId())) {
                $job
                    ->setJobType(Diglin_Ricento_Model_Sync_Job::TYPE_SYNC)
                    ->setProductsListingId($productListing->getId());
            }

            $job
                ->setTotalCount($totalItems)
                ->setTotalProceed(0)
                ->setStatus(Diglin_Ricento_Model_Sync_Job::STATUS_PENDING)
                ->save();

            // Create a job to check the product items before to sync to Ricardo.ch

            $jobCheck = clone $job;

            // Reload a previous check job and init it

            $jobCheck
                ->setId(null)
                ->loadByJobTypeAndProductsListingId(Diglin_Ricento_Model_Sync_Job::TYPE_CHECK, $productListing->getId());

            $jobCheck
                ->setTotalCount($totalItems)
                ->setTotalProceed(0)
                ->setJobType(Diglin_Ricento_Model_Sync_Job::TYPE_CHECK)
                ->setStatus(Diglin_Ricento_Model_Sync_Job::STATUS_PENDING)
                ->setJobMessage(new Zend_Db_Expr('null'))
                ->setLastItemId(new Zend_Db_Expr('null'))
                ->setStartedAt(new Zend_Db_Expr('null'))
                ->save();

            $this->_getSession()->addSuccess($this->__('The job to check your products listing will start in few moment. You can check the progression in the grid below.'));
            $this->_redirect('*/*/index', array('id' => $job->getId()));
        } else {
            $this->_redirect('*/products_listing');
        }
    }

    /**
     * Ajax call to get the status of a running job
     */
    public function checkAjaxAction()
    {
        $jobId = (int)$this->getRequest()->getParam('jobid');
        $isAjax = (bool)($this->getRequest()->getQuery('isAjax', false) || $this->getRequest()->getQuery('ajax', false));
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
            $totalProceed = $job->getTotalProceed();
            $totalCount = $job->getTotalCount();

            if ($totalCount) {
                $percentDone = ($totalProceed * 100) / $totalCount;
            }

            // @todo get the job messages
            $state = '';
            $message = '';

            if ($percentDone == 100) {
                switch ($state) {
                    case 'ok':
                        $message = $this->__('No problem found');
                        break;
                    case 'warning':
                        $message = $this->__('Warning - click here');
                        break;
                    case 'error':
                        $message = $this->__('Error - click here');
                        break;
                }
            }

            $response = array(
                'percentage' => $percentDone,
                'status' => $job->getStatus(),
                'state' => $state,
                'message' => $message

            );
        }

        $this->getResponse()->setBody(MAge::helper('core')->jsonEncode($response));
        return;
    }

    /**
     * Ajax call to get the status of a running job
     */
    public function checkAjaxPopupAction()
    {
        $jobId = (int)$this->getRequest()->getParam('jobid');

        $job = Mage::getModel('diglin_ricento/sync_job')->load($jobId);

        $this->loadLayout();

        $block = $this->getLayout()->getBlock('sync_ajax_popup');

        if ($job->getId() && $block) {
            $messages = $this->_getCheckMessages($job);
            if (!empty($messages)) {
                $block->setMessages($job->getJobMessage());
            }
        }

        $this->renderLayout();
    }

    /**
     * Get error or warning messages
     *
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @return array
     */
    protected function _getCheckMessages(Diglin_Ricento_Model_Sync_Job $job)
    {
        $message = $job->getJobMessage();

        //@todo prepare the message output

        return $message;
    }

    /**
     * @return boolean
     */
    protected function _savingAllowed() {}
}