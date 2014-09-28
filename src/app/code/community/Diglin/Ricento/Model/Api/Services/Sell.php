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
        $insertArticle = $item->getInsertArticleParameter();

        try {
            $this->_prepareCredentialToken();

            // @todo insert for each associated products in case of configurable

            $articleResult = $this->getServiceModel()->insertArticle($insertArticle);

            $this->_updateCredentialToken();

        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            $this->_updateCredentialToken();
            Mage::logException($e);

            if (Mage::helper('diglin_ricento')->isDebugEnabled()) {
                $insertArticle->setPictures(null, true); // remove picture otherwise log is extremely long
                Mage::log($insertArticle->getDataProperties(), Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);
            }

            $this->_handleSecurityException($e);
        }
        return $articleResult;
    }

    public function updateArticle()
    {

        return $this;
    }

    /**
     * @param Diglin_Ricento_Model_Products_Listing_Item $item
     * @return array|bool
     */
    public function relistArticle(Diglin_Ricento_Model_Products_Listing_Item $item)
    {
        $relistArticleResult = array();

        if (!$item->getRicardoArticleId()) {
            return false;
        }

        try {
            $this->_prepareCredentialToken();

            // @todo insert for each associated products in case of configurable

            $relistArticleResult = $this->getServiceModel()->relistArticle($item->getRicardoArticleId());

            $this->_updateCredentialToken();

        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            $this->_updateCredentialToken();
            Mage::logException($e);
            $this->_handleSecurityException($e);
        }

        return $relistArticleResult;
    }

    /**
     * @param Diglin_Ricento_Model_Products_Listing_Item $item
     * @return array|bool
     * @throws Exception
     */
    public function stopArticle(Diglin_Ricento_Model_Products_Listing_Item $item)
    {
        $closeArticleParameter = $item->getCloseArticleParameter();

        if (!$closeArticleParameter) {
            return false;
        }

        try {
            $this->_prepareCredentialToken();

            // @todo insert for each associated products in case of configurable

            try {

                $closeArticleResult = $this->getServiceModel()->closeArticle($closeArticleParameter);

                $this->_updateCredentialToken();

                /**
                 * Ricardo API is special here - if article is closed, returned values may be empty even if successful !!!
                 * If it doesn't work, an exception is triggered
                 */
                if (isset($closeArticleResult['IsClosed'])) {
                    return true;
                }
            } catch (Exception $e) {
                switch ($e->getCode()) {
                    case \Diglin\Ricardo\Enums\ArticleErrors::ARTICLENOTFOUND:
                    case \Diglin\Ricardo\Enums\GeneralErrors::CLOSEAUCTIONFAILED:
                    case \Diglin\Ricardo\Enums\GeneralErrors::CLOSECLASSIFIEDFAILED:
                        // do nothing pass to the DeletePlannedArticle
                        break;
                    default:
                        throw $e;
                }
            }

            $deleteArticleParameter = $item->getDeleteArticleParameter();
            if (!$deleteArticleParameter) {
                return false;
            }

            $closeArticleResult = $this->getServiceModel()->deletePlannedArticle($deleteArticleParameter);

            $this->_updateCredentialToken();

            /**
             * Ricardo API is special here - if article is closed, returned values may be empty !!!
             * If it doesn't work, an exception is triggered
             */
            if (isset($closeArticleResult['IsClosed'])) {
                return true;
            }

        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            $this->_updateCredentialToken();
            Mage::logException($e);
            $this->_handleSecurityException($e);
        }

        return false;
    }
}