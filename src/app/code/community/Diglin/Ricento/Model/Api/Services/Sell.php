<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Model_Api_Services_Sell
 */
class Diglin_Ricento_Model_Api_Services_Sell extends Diglin_Ricento_Model_Api_Services_Abstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'sell';

    /**
     * @var string
     */
    protected $_model = '\Diglin\Ricardo\Managers\Sell';

    /**
     * @var Diglin_Ricento_Model_Sales_Options
     */
    protected $_salesOptions;

    /**
     * @var Diglin_Ricento_Model_Rule
     */
    protected $_shippingPaymentRule;

    /**
     * Overwritten just to get the class/method auto completion
     * Be aware that using directly this method to use the methods of the object instead of using
     * the magic methods of the abstract (__call, __get, __set) will prevent to use the cache of Magento
     *
     * @return \Diglin\Ricardo\Managers\Sell
     */
    public function getServiceModel()
    {
        return parent::getServiceModel();
    }

    /**
     * @param Diglin_Ricento_Model_Products_Listing_Item $item
     * @return array
     */
    public function insertArticle(Diglin_Ricento_Model_Products_Listing_Item $item)
    {
        $articleResult = array();

        try {
            $this->_prepareCredentialToken();

            // @todo insert for each associated products in case of configurable
            $insertArticle = $item->getInsertArticleParameter();

            $articleResult = $this->getServiceModel()->insertArticle($insertArticle);

            $this->_updateCredentialToken();

        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            Mage::logException($e);
            $insertArticle->setPictures(null, true); // remove picture otherwise log is extremely long
            Mage::log($insertArticle->getDataProperties(), Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);

            $this->_handleSecurityException($e);
        }
        return $articleResult;
    }

    /**
     * @param \Diglin\Ricardo\Managers\Sell\Parameter\InsertArticlesParameter $articles
     * @return array
     */
    public function insertArticles(\Diglin\Ricardo\Managers\Sell\Parameter\InsertArticlesParameter $articles)
    {
        $articleResult = array();

        try {
            $this->_prepareCredentialToken();

            $articleResult = $this->getServiceModel()->insertArticles($articles);

            $this->_updateCredentialToken();

        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            Mage::logException($e);
            $this->_handleSecurityException($e);
        }
        return $articleResult;
    }

    public function updateArticle()
    {

        return $this;
    }
    public function relistArticle()
    {

        return $this;
    }

    /**
     * @param Diglin_Ricento_Model_Products_Listing_Item $item
     * @return array
     */
    public function stopArticle(Diglin_Ricento_Model_Products_Listing_Item $item)
    {
        $articleResult = array();

        try {
            $this->_prepareCredentialToken();

            // @todo insert for each associated products in case of configurable

            $closeArticle = $item->getCloseArticleParameter();

            $articleResult = $this->getServiceModel()->closeArticle($closeArticle);

            $this->_updateCredentialToken();

        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            Mage::logException($e);
            Mage::log($closeArticle->getDataProperties(), Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);

            $this->_handleSecurityException($e);
        }
        return $articleResult;
    }
}