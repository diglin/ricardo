<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
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
     * but also some logic related to the secure token
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
            $start = microtime(true);
            $insertArticle = $item->getInsertArticleParameter();

            $articleResult = parent::insertArticle($insertArticle);

            if (Mage::helper('diglin_ricento')->isDebugEnabled()) {
                Mage::log('Time to insert article ' . (microtime(true) - $start) . ' sec', Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);
            }
        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            $this->_updateCredentialToken();
            Mage::logException($e);

            if (Mage::helper('diglin_ricento')->isDebugEnabled()) {
                $insertArticle->setPictures(null, true); // remove picture otherwise log is extremely long
                Mage::log($insertArticle->getDataProperties(), Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);
            }

            $this->_handleSecurityException($e);
        }

        unset($insertArticle);

        return $articleResult;
    }

    public function updateArticle(Diglin_Ricento_Model_Products_Listing_Item $item)
    {
        // @todo when needed
        return $this;
    }

    /**
     * Not used
     *
     * @param Diglin_Ricento_Model_Products_Listing_Item $item
     * @return array|bool
     */
    public function relistArticle(Diglin_Ricento_Model_Products_Listing_Item $item)
    {
        $relistArticleResult = array();

        if (!$item->getRicardoArticleId() || $item->getIsPlanned()) {
            return false;
        }

        try {
            $relistArticleResult = parent::relistArticle($item->getRicardoArticleId());

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
        /**
         * If it is a planned article, we have to delete instead to close the article
         */
        if ($item->getIsPlanned()) {
            $parameterMethod = 'getDeleteArticleParameter';
            $serviceMethod = 'deletePlannedArticle';
        } else {
            $parameterMethod = 'getCloseArticleParameter';
            $serviceMethod = 'closeArticle';
        }

        try {
            $parameter = $item->$parameterMethod();
            if (!$parameter) {
                return false;
            }

            $result = $this->$serviceMethod($parameter);

            /**
             * Ricardo API is special here - if article is closed, returned values may be empty !!!
             * If it's closed/deleted or an error occurred, an exception is triggered
             */
            if (isset($result['IsClosed'])) {
                unset($parameter);
                return true;
            }
        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            $this->_updateCredentialToken();
            Mage::logException($e);

            try {
                $this->_handleSecurityException($e);
            } catch (\Diglin\Ricardo\Exceptions\GeneralException $e) {
                switch ($e->getCode()) {
                    case \Diglin\Ricardo\Enums\GeneralErrors::DELETEPLANNEDFAILED:
                    case \Diglin\Ricardo\Enums\GeneralErrors::CLOSEAUCTIONFAILED:
                        return false;
                    default:
                        break;
                }
                throw $e;
            }
        }

        return false;
    }
}