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
 * Class Diglin_Ricento_Adminhtml_LogController
 */
class Diglin_Ricento_Adminhtml_LogController extends Diglin_Ricento_Controller_Adminhtml_Action
{
    protected function _initAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('diglin_ricento')->__('Logs'));

        return $this;
    }

    public function indexAction()
    {
        $this->_redirect('*/*/listing');
    }

    /**
     * Show logs of the listings
     */
    public function listingAction()
    {
        $this->_initAction();

        $productsListing = null;
        if ($this->getRequest()->getParam('id')) {
            $productsListing = $this->_initListing();
        }

        $productsListingId = $productsListing->getId();
        if ($productsListing && !empty($productsListingId)) {
            $block = $this->getLayout()->createBlock('diglin_ricento/adminhtml_products_listing_log');
        } else {
            $block = $this->getLayout()->createBlock('diglin_ricento/adminhtml_log', 'ricento_logs_tabs',
                array('active_tab' => Diglin_Ricento_Block_Adminhtml_Log_Tabs::TAB_LISTING)
            );
        }

        $this->_addContent($block)->renderLayout();
    }

    /**
     * Show synchronization job processes
     */
    public function syncAction()
    {
        $this->_initAction();

        $block = $this->getLayout()->createBlock('diglin_ricento/adminhtml_log', 'ricento_logs_tabs',
            array('active_tab' => Diglin_Ricento_Block_Adminhtml_Log_Tabs::TAB_SYNCHRONIZATION)
        );

        $this->_addContent($block)->renderLayout();
    }

    /**
     * Show orders passed
     */
    public function orderAction()
    {
        $this->_initAction();

        $block = $this->getLayout()->createBlock('diglin_ricento/adminhtml_log', 'ricento_logs_tabs',
            array('active_tab' => Diglin_Ricento_Block_Adminhtml_Log_Tabs::TAB_ORDER)
        );

        $this->_addContent($block)->renderLayout();
    }

    /**
     * Massaction to delete listing logs
     */
    public function massListingDeleteAction()
    {
        $logs = $this->getRequest()->getParam('listing_logs_grid');

        try {
            if (is_array($logs)) {
                $logCollection = Mage::getResourceModel('diglin_ricento/products_listing_log_collection');
                $logCollection
                    ->addFieldToFilter('log_id', array('in' => $logs));

                $logCollection->walk('delete');
                $this->_getSession()->addSuccess($this->__('Log(s) is/are successfully deleted.'));
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($this->__('An error occurred while trying to delete the listing log(s). Please, check your exception log.'));
        }

        $this->_redirect('*/*/listing');
    }

    /**
     * Massaction to delete sync jobs
     */
    public function massSyncDeleteAction()
    {
        $jobs = $this->getRequest()->getParam('jobs_grid');

        try {
            if (is_array($jobs)) {
                $jobCollection = Mage::getResourceModel('diglin_ricento/sync_job_collection');
                $jobCollection
                    ->addFieldToFilter('job_id', array('in' => $jobs))
                    ->addFieldToFilter('progress', array('neq' => Diglin_Ricento_Model_Sync_Job::PROGRESS_RUNNING));

                $goingToBeDeleted = $jobCollection->getAllIds();

                $jobCollection->walk('delete');
                $this->_getSession()->addSuccess($this->__('Job(s) is/are successfully deleted.'));

                $notDeleted = array_diff($jobs, $goingToBeDeleted);
                if ($notDeleted) {
                    $this->_getSession()->addNotice($this->__('The following job IDs have not been deleted because they are still running: ' . implode(',', $notDeleted)));
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($this->__('An error occurred while trying to delete the job(s). Please, check your exception log.'));
        }

        $this->_redirect('*/*/sync');
    }
}