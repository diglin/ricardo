<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Cron
{
    public function process()
    {
        $this->_processCheckItems();
    }

    protected function _processCheckItems()
    {
        // @todo check no double execution

        $jobsCollection = Mage::getResourceModel('diglin_ricento/sync_job_collection');
        $jobsCollection
            ->addFieldToFilter('job_type', Diglin_Ricento_Model_Sync_Job::TYPE_CHECK)
            ->addFieldToFilter('status', Diglin_Ricento_Model_Sync_Job::STATUS_PENDING);

        foreach ($jobsCollection->getItems() as $job) {

            $message = array();
            $totalProceed = 0;
            $lastItemId = 0;

            $job
                ->setStartedAt(Mage::getSingleton('core/date')->gmtDate())
                ->setStatus(Diglin_Ricento_Model_Sync_Job::STATUS_RUNNING)
                ->save();

            if ($job->getProductsListingId()) {

                $start = microtime(true);

                $itemCollection = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
                $itemCollection
                    ->addFieldToFilter('status', array('in' => Diglin_Ricento_Helper_Data::STATUS_PENDING))
                    ->addFieldToFilter('products_listing_id', array('eq' => $job->getProductsListingId()))
                    ->setOrder('item_id', 'DESC');

                $totalProceed = $job->getTotalProceed();

                foreach ($itemCollection->getItems() as $item) {

                    $itemValidator = new Diglin_Ricento_Model_Validate_Products_Item();
                    if (!$itemValidator->isValid($item)) {
                        $message[$item->getProductId()] = $itemValidator->getMessages(); // @todo get the correct error/warning messages
                    }

                    ++$totalProceed;
                    $lastItemId = $item->getId();
                    $job->saveCurrentJob(Diglin_Ricento_Model_Sync_Job::STATUS_RUNNING, $totalProceed, $lastItemId);
                }

                $end = microtime(true);
                Mage::log('Time to proceed process check for job id ' . $job->getId() . ' in ' . ($end-$start) . ' sec', Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);
            }

            $job
                ->setTotalProceed($totalProceed)
                ->setLastItemId($lastItemId)
                ->setJobMessage(Mage::helper('core')->jsonEncode($message))
                ->setStatus(Diglin_Ricento_Model_Sync_Job::STATUS_COMPLETED)
                ->save();
        }

    }
}