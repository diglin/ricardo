<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Services;

use Diglin\Ricardo\Core\Helper;
use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Enums\CloseListStatus;

/**
 * Class SellerAccount
 *
 * Refers to the account as a seller:
 * get all open articles, get sold articles, get articles that haven't been sold
 *
 * @package Diglin\Ricardo\Services
 * @link https://ws.ricardo.ch/RicardoApi/documentation/html/Methods_T_Ricardo_Contracts_ISellerAccountService.htm
 */
class SellerAccount extends ServiceAbstract
{
    /**
     * @var string
     */
    protected $_service = 'SellerAccountService';

    /**
     * @var string
     */
    protected $_typeOfToken = self::TOKEN_TYPE_IDENTIFIED;

    /**
     * Adds the card payment option to specified articles.
     */
    public function addCardPaymentOption()
    {
        $articleIds = array();

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['article_ids'])) ? $articleIds = $args[0]['article_ids'] : '';
        };

        if (is_numeric($articleIds)) {
            $articleIds = array($articleIds);
        }

        return array(
            'method' => 'AddCardPaymentOption',
            'params' => array('addCardPaymentOptionParameter' => array(
                'ArticleIds' => $articleIds
            ))
        );
    }

    /**
     * Asserts the article modification.
     */
    public function assertArticleModification()
    {
        $articleId = null;

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['article_id'])) ? $articleId = $args[0]['article_id'] : '';
        };

        return array(
            'method' => 'AssertArticleModification',
            'params' => array('assertArticleModificationParameter' => array(
                'ArticleId' => $articleId
            ))
        );
    }

    /**
     * Get if the article can be modified or not
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "AssertArticleModificationResult": {
     *       "CanModify": "BOOLEAN"
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return bool
     */
    public function assertArticleModificationResult(array $data)
    {
        if (isset($data['AssertArticleModificationResult']) && isset($data['AssertArticleModificationResult']['CanModify'])) {
            return $data['AssertArticleModificationResult']['CanModify'];
        }
        return false;
    }

    /**
     * Asserts the classified modification.
     */
    public function assertClassifiedModification()
    {
        $classifiedId = null;

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['classified_id'])) ? $classifiedId = $args[0]['classified_id'] : '';
        };

        return array(
            'method' => 'AssertClassifiedModification',
            'params' => array('assertClassifiedModificationParameter' => array(
                'ClassifiedId' => $classifiedId
            ))
        );
    }

    /**
     * Get if the classified can be modified or not
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "AssertClassifiedModificationResult": {
     *       "CanModify": "BOOLEAN"
     *     }
     * }
     * </pre>
     *
     * @param array $data
     * @return bool
     */
    public function assertClassifiedModificationResult(array $data)
    {
        if (isset($data['AssertClassifiedModificationResult']) && isset($data['AssertClassifiedModificationResult']['CanModify'])) {
            return $data['AssertClassifiedModificationResult']['CanModify'];
        }
        return false;
    }


    /**
     * Gets an article.
     */
    public function getArticle()
    {
        $articleId = null;

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['article_id'])) ? $articleId = $args[0]['article_id'] : '';
        };

        return array(
            'method' => 'GetArticle',
            'params' => array('getArticleParameter' => array(
                'ArticleId' => $articleId
            ))
        );
    }

    public function getArticleResult(array $data)
    {
        if (isset($data['GetArticleResult'])) {
            return $data['GetArticleResult'];
        }
        return array();
    }

    /**
     * Get all auctions by date and type
     *
     * @return array
     */
    public function getArticles()
    {
        $articleTypes = ArticlesTypes::ALL;
        $closeStatus = CloseListStatus::CLOSED;
        $isPlannedArticles = true;
        $lastModificationDate = Helper::getJsonDate();

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['article_types'])) ? $articleTypes = (int) $args[0]['article_types'] : '';
            (isset($args[0]['close_status'])) ? $closeStatus = (int) $args[0]['close_status'] : '';
            (isset($args[0]['is_planned_articles'])) ? $isPlannedArticles = (int) $args[0]['is_planned_articles'] : '';
            (!empty($args[0]['last_modification_date'])) ? $lastModificationDate = $args[0]['last_modification_date'] : '';
        };

        return array(
            'method' => 'GetArticles',
            'params' => array('getAuctionsParameter' => array(
                'ArticlesType' => $articleTypes, // required
                'CloseStatus' => $closeStatus, // required
                'IsPlannedArticles' => $isPlannedArticles, // optional
                'LastModificationDate' => $lastModificationDate // optional
            ))
        );
    }

    /**
     *
     *
     *
     * @param array $data
     * @return array
     */
    public function getArticlesResult(array $data)
    {
        if (isset($data['GetArticlesResult']) && isset($data['GetArticlesResult']['Articles'])) {
            return $data['GetArticlesResult']['Articles'];
        }
        return array();
    }

    /**
     * Gets a classified.
     */
    public function getClassified()
    {
        return array(
            'method' => 'GetClassified',
            'params' => array('GetClassifiedParameter')
        );
    }

    /**
     * Get all classifieds by date and type
     */
    public function getClassifieds()
    {
        return array(
            'method' => 'GetClassifieds',
            'params' => array('GetClassifiedsParameter')
        );
    }


    /**
     * Get all articles that were closed by customer
     */
    public function getClosedArticles()
    {
        return array(
            'method' => 'GetClosedArticles',
            'params' => array('GetClosedArticlesParameter')
        );
    }


    /**
     * Get all classified items that were closed by customer
     */
    public function getClosedClassifieds()
    {
        return array(
            'method' => 'GetClosedClassifieds',
            'params' => array('GetClosedClassifiedsParameter')
        );
    }


    /**
     * Gets an open article.
     */
    public function getOpenArticle()
    {
        return array(
            'method' => 'GetOpenArticle',
            'params' => array('GetOpenArticleParameter')
        );
    }


    /**
     * Gets the open articles.
     */
    public function getOpenArticles()
    {
        return array(
            'method' => 'GetOpenArticles',
            'params' => array('GetOpenArticlesParameter')
        );
    }


    /**
     * Gets the payment options for a seller.
     *
     * @param int $customerId
     * @return array
     */
    public function getPaymentOptions($customerId = null)
    {
        return array(
            'method' => 'GetPaymentOptions',
            'params' => array('GetPaymentOptionsParameter' => array('CustomerId' => $customerId))
        );
    }

    /**
     *  Gets the payment conditions and payment function associated result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetPaymentOptionsResult": {
     *       "CardPaymentActiveOnAllProducts": BOOL
     *       "CardPaymentOptionAvailable": BOOL
     *       "CustomerId": INT
     *       "LastPaymentOptionUpdateDate": DATETIME
     *      }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getPaymentOptionsResult($data)
    {
        if (isset($data['GetPaymentOptionsResult'])) {
            return $data['GetPaymentOptionsResult'];
        }
        return array();
    }

    /**
     * Gets a planned articles.
     */
    public function getPlannedArticle()
    {
        return array(
            'method' => 'GetPlannedArticle',
            'params' => array('GetPlannedArticleParameter')
        );
    }

    /**
     * Gets the planned articles.
     */
    public function getPlannedArticles()
    {
        return array(
            'method' => 'GetPlannedArticles',
            'params' => array('GetPlannedArticlesParameter')
        );
    }

    /**
     * Gets the planned pictures.
     */
    public function getPlannedPictures()
    {
        return array(
            'method' => 'GetPlannedPictures',
            'params' => array('GetPlannedPicturesParameter')
        );
    }

    /**
     * Gets list of customer's listing packages(normally should be just one item)
     */
    public function getSellerPackages()
    {
        return array(
            'method' => 'GetSellerPackages',
            'params' => array('getSellerPackagesParameter' => array(
                'PackageType' => 0
            ))
        );
    }

    public function getSellerPackagesResult(array $data)
    {
        if (isset($data['GetSellerPackagesResult'])) {
            return $data['GetSellerPackagesResult'];
        }
        return array();
    }

    /**
     * Gets the sold article.
     */
    public function getSoldArticle()
    {
        return array(
            'method' => 'GetSoldArticle',
            'params' => array('GetSoldArticleParameter')
        );
    }

    public function getSoldArticles()
    {
        return array(
            'method' => 'GetSoldArticles',
            'params' => array('getSoldArticlesParameter')
        );
    }

    /**
     * Get available article templates
     *
     * @return array
     */
    public function getTemplates()
    {
        return array(
            'method' => 'GetTemplates',
            'params' => array('getTemplatesParameter' => array())
        );
    }

    /**
     * Get the list of templates available
     *
     * @param array
     * @return array
     */
    public function getTemplatesResult($data)
    {
        if (isset($data['GetTemplatesResult']) && isset($data['GetTemplatesResult']['Templates'])) {
            return $data['GetTemplatesResult']['Templates'];
        }
        return array();
    }

    /**
     * Gets the unsold article.
     */
    public function getUnsoldArticle()
    {
        return array(
            'method' => 'GetUnsoldArticle',
            'params' => array('getUnsoldArticleParameter' => array())
        );
    }

    /**
     * Gets the unsold articles.
     */
    public function getUnsoldArticles()
    {
        return array(
            'method' => 'GetUnsoldArticles',
            'params' => array('getUnsoldArticlesParameter' => array())
        );
    }

    /**
     * Inserts the answer.
     */
    public function insertAnswer()
    {
        return array(
            'method' => 'InsertAnswer',
            'params' => array('InsertAnswerParameter' => array())
        );
    }

    /**
     * Inserts selected by customer listing package
     */
    public function insertSellerPackage()
    {
        return array(
            'method' => 'InsertSellerPackage',
            'params' => array('InsertSellerPackageParameter' => array())
        );
    }

    /**
     * Removes the card payment option from specified articles.
     */
    public function removeCardPaymentOption()
    {
        return array(
            'method' => 'RemoveCardPaymentOption',
            'params' => array('RemoveCardPaymentOptionParameter' => array())
        );
    }

    /**
     * Sets if the article has cumulative shipping.
     */
    public function setCumulativeShipping()
    {
        return array(
            'method' => 'SetCumulativeShipping',
            'params' => array('SetCumulativeShippingParameter' => array())
        );
    }

    /**
     * Change the automatic reactivation for a premium package
     */
    public function setPremiumPackageAutomaticReactivation()
    {
        return array(
            'method' => 'SetPremiumPackageAutomaticReactivation',
            'params' => array('SetPremiumPackageAutomaticReactivationParameter' => array())
        );
    }
}