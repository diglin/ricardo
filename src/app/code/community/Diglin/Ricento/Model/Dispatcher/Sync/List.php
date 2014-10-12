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

use \Diglin\Ricardo\Enums\Article\ArticlesTypes;
use \Diglin\Ricardo\Managers\SellerAccount\Parameter\OpenArticlesParameter;
use \Diglin\Ricardo\Managers\SellerAccount\Parameter\SoldArticlesParameter;
use \Diglin\Ricardo\Managers\SellerAccount\Parameter\UnsoldArticlesParameter;

/**
 * Class Diglin_Ricento_Model_Dispatcher_Sync_List
 */
class Diglin_Ricento_Model_Dispatcher_Sync_List extends Diglin_Ricento_Model_Dispatcher_Abstract
{
    /**
     * @var int
     */
    protected $_logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_SYNCLIST;

    /**
     * @var string
     */
    protected $_jobType = Diglin_Ricento_Model_Sync_Job::TYPE_SYNCLIST;

    /**
     * @var array
     */
    protected $_openedArticles;

    /**
     * @return $this
     */
    public function proceed()
    {
        $jobType = Diglin_Ricento_Model_Sync_Job::TYPE_SYNCLIST;

        $productsListingResource = Mage::getResourceModel('diglin_ricento/products_listing');
        $readListingConnection = $productsListingResource->getReadConnection();
        $select = $readListingConnection->select()
            ->from($productsListingResource->getTable('diglin_ricento/products_listing'), 'entity_id');

        $listingIds = $readListingConnection->fetchCol($select);

        foreach ($listingIds as $listingId) {

            /**
             * We want items listed and planned because we want to get the new ricardo_article_id
             */
            $itemResource = Mage::getResourceModel('diglin_ricento/products_listing_item');
            $readConnection = $itemResource->getReadConnection();
            $select = $readConnection->select()
                ->from($itemResource->getTable('diglin_ricento/products_listing_item'), 'item_id')
                ->where('products_listing_id = :id')
                ->where('is_planned = 1')
                ->where('status = ?', Diglin_Ricento_Helper_Data::STATUS_LISTED);

            $binds  = array('id' => $listingId);
            $countListedItems = count($readConnection->fetchAll($select, $binds));

            if ($countListedItems == 0) {
                continue;
            }

            $job = Mage::getModel('diglin_ricento/sync_job');
            $job
                ->setJobType($jobType)
                ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING)
                ->setJobMessage(array($job->getJobMessage()))
                ->save();

            $jobListing = Mage::getModel('diglin_ricento/sync_job_listing');
            $jobListing
                ->setProductsListingId($listingId)
                ->setTotalCount($countListedItems)
                ->setTotalProceed(0)
                ->setJobId($job->getId())
                ->save();
        }

        return parent::proceed();
    }

    /**
     * We update the ricardo article ID because it changes when it's planned or opened
     * We need it to retrieve the sold article in a later process
     *
     * @return $this
     */
    protected function _proceed()
    {
        $jobListing = $this->_currentJobListing;

        $itemCollection = $this->_getItemCollection(array(Diglin_Ricento_Helper_Data::STATUS_LISTED), $jobListing->getLastItemId());
        $itemCollection->addFieldToFilter('is_planned', 1);

        /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
        foreach ($itemCollection->getItems() as $item) {

            $article = null;
            $isUnsold =  false;
            $openParameter = new OpenArticlesParameter();
            $openParameter->setInternalReferenceFilter($item->getInternalReference());

            try {
                $openArticles = $this->_getSellerAccount()->getServiceModel()->getOpenArticles($openParameter);

                /**
                 * Get Article information from OpenArticles method
                 */
                if (count($openArticles['OpenArticles']) > 0) {
                    $article = Mage::helper('diglin_ricento')->extractData($openArticles['OpenArticles'][0]); // Only one element expected but more may come
                }

                /**
                 * We may have missed the article Id value changes in OpenArticles method, due to sales for example
                 * so we try to get it from sold articles method
                 */
                if (is_null($article)) {
                    $article = $this->_getSoldArticles($item);
                }

                /**
                 * It's maybe not sold
                 */
                if (is_null($article)) {
                    $article = $this->_getUnsoldArticles($item);
                    if (!is_null($article)) {
                        $isUnsold = true;
                    }
                }

                if ($article) {
                    /**
                     * Get the new ricardo article id if the article was planned before
                     */
                    if ($article->getArticleId() && !$isUnsold) {
                        $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                        $this->_itemMessage = array('success' => $this->_getHelper()->__('The product is now live on ricardo.ch'));
                        $item->getResource()->saveCurrentItem($item->getId(), array('is_planned' => 0, 'ricardo_article_id' => $article->getArticleId()));
                    } elseif ($article->getArticleId() && $isUnsold) {
                        $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                        $this->_itemMessage = array('success' => $this->_getHelper()->__('Sorry, the product has not been sold'));
                        $item->getResource()->saveCurrentItem($item->getId(), array('status' => Diglin_Ricento_Helper_Data::STATUS_STOPPED, 'is_planned' => 0, 'ricardo_article_id' => $article->getArticleId()));
                    }
                }
            } catch (Exception $e) {
                $this->_handleException($e);
                $e = null;
                // keep going for the next item - no break
            }

            /**
             * Save item information and eventual error messages
             */
            if (!is_null($this->_itemMessage)) {
                $this->_getListingLog()->saveLog(array(
                    'job_id' => $this->_currentJob->getId(),
                    'product_title' => $item->getProductTitle(),
                    'products_listing_id' => $this->_productsListingId,
                    'product_id' => $item->getProductId(),
                    'message' => $this->_jsonEncode($this->_itemMessage),
                    'log_status' => $this->_itemStatus,
                    'log_type' => $this->_logType
                ));
            }

            /**
             * Save the current information of the process to allow live display via ajax call
             */
            $jobListing->saveCurrentJob(array(
                'total_proceed' => ++$this->_totalProceed,
                'last_item_id' => $item->getId()
            ));

            $this->_itemMessage = null;
            $this->_itemStatus = null;
        }

        return $this;
    }

    /**
     * @param $item
     * @return null|Varien_Object
     */
    protected function _getSoldArticles($item)
    {
        $article = null;
        $soldArticlesParameter = new SoldArticlesParameter();

        /**
         * Set end date to filter e.g. last day. Do not use a higher value as the minimum sales duration is 1 day,
         * we prevent to have conflict with several sold articles having similar internal reference
         */
        $soldArticlesParameter
            ->setInternalReferenceFilter($item->getInternalReference())
            ->setMinimumEndDate($this->_getHelper()->getJsonDate(time() - (1 * 24 * 60 * 60)));

        $articles = $this->_getSellerAccount()->getServiceModel()->getSoldArticles($soldArticlesParameter);
        if (count($articles) > 0) {
            $article = $this->_getHelper()->extractData($articles[0]);
        }

        return $article;
    }

    /**
     * @param $item
     * @return null|Varien_Object
     */
    protected function _getUnsoldArticles($item)
    {
        $article = null;
        $unsoldArticlesParameter = new UnsoldArticlesParameter();

        $unsoldArticlesParameter
            ->setInternalReferenceFilter($item->getInternalReference())
            ->setMinimumEndDate($this->_getHelper()->getJsonDate(time() - (1 * 24 * 60 * 60)));

        $articles = $this->_getSellerAccount()->getServiceModel()->getUnsoldArticles($unsoldArticlesParameter);
        if (count($articles) > 0) {
            $article = $this->_getHelper()->extractData($articles[0]);
        }

        return $article;
    }

    /**
     * @return Diglin_Ricento_Model_Api_Services_Selleraccount
     */
    protected function _getSellerAccount()
    {
        return Mage::getSingleton('diglin_ricento/api_services_selleraccount');
    }
}