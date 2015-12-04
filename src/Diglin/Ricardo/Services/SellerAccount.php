<?php
/**
 * Diglin GmbH - Switzerland
 *
 * This file is part of a Diglin GmbH module.
 *
 * This Diglin GmbH module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
namespace Diglin\Ricardo\Services;

use Diglin\Ricardo\Core\Helper;
use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\ArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\ClosedArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\GetInTransitionArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\OpenArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\PlannedArticleParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\SoldArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\UnsoldArticlesParameter;

/**
 * Class SellerAccount
 *
 * Refers to the account as a seller:
 * get all open articles, get sold articles, get articles that haven't been sold, etc
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
     * Adds the card payment option to specified articles parameters
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
     * Asserts the article modification parameters
     *
     * @param $articleId
     * @return array
     */
    public function assertArticleModification($articleId)
    {
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
     * Gets an article parameter
     */
    public function getArticle($articleId)
    {
        return array(
            'method' => 'GetArticle',
            'params' => array('getArticleParameter' => array(
                'ArticleId' => $articleId
            ))
        );
    }

    /**
     * Gets an article result.
     *
     * @param array $data
     * @return array
     */
    public function getArticleResult(array $data)
    {
        if (isset($data['GetArticleResult'])) {
            return $data['GetArticleResult'];
        }
        return array();
    }

    /**
     * Get all auctions by date and type parameters
     *
     * @param ArticlesParameter $parameter
     * @return array
     */
    public function getArticles(ArticlesParameter $parameter)
    {
        return array(
            'method' => 'GetArticles',
            'params' => array('getAuctionsParameter' => $parameter->getDataProperties())
        );
    }

    /**
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
     * Get all articles that were closed by customer parameters
     *
     * @param ClosedArticlesParameter $parameter
     * @return array
     */
    public function getClosedArticles(ClosedArticlesParameter $parameter)
    {
        return array(
            'method' => 'GetClosedArticles',
            'params' => array('getClosedArticlesParameter' => $parameter->getDataProperties())
        );
    }

    /**
     * Result to get all articles that were closed by customer
     *
     * @param array $data
     * @return array
     */
    public function getClosedArticlesResult(array $data)
    {
        if (isset($data['GetClosedArticlesResult']) && isset($data['GetClosedArticlesResult']['Auctions'])) {
            return $data['GetClosedArticlesResult']['Auctions'];
        }
        return array();
    }

    /**
     * Gets an open article parameter
     *
     * @param int $articleId
     * @return array
     */
    public function getOpenArticle($articleId)
    {
        return array(
            'method' => 'GetOpenArticle',
            'params' => array('getOpenArticleParameter' => array('ArticleId' => $articleId))
        );
    }

    /**
     * Gets an open article result
     *
     * @param array $data
     * @return array
     */
    public function getOpenArticleResult(array $data)
    {
        if (isset($data['GetOpenArticleResult'])) {
            return $data['GetOpenArticleResult'];
        }
        return array();
    }

    /**
     * Gets open articles parameter
     *
     * @param OpenArticlesParameter $parameter
     * @return array
     */
    public function getOpenArticles(OpenArticlesParameter $parameter)
    {
        return array(
            'method' => 'GetOpenArticles',
            'params' => array('getOpenArticlesParameter' => $parameter->getDataProperties())
        );
    }

    /**
     * Gets open articles result
     *
     * @param array $data
     * @return array
     */
    public function getOpenArticlesResult(array $data)
    {
        if (isset($data['GetOpenArticlesResult'])) {
            return $data['GetOpenArticlesResult'];
        }
        return array();
    }


    /**
     * Gets the payment options for a seller parameter
     *
     * @param int $customerId
     * @return array
     */
    public function getPaymentOptions($customerId)
    {
        return array(
            'method' => 'GetPaymentOptions',
            'params' => array('getPaymentOptionsParameter' => array('CustomerId' => $customerId))
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
    public function getPaymentOptionsResult(array $data)
    {
        if (isset($data['GetPaymentOptionsResult'])) {
            return $data['GetPaymentOptionsResult'];
        }
        return array();
    }

    /**
     * Gets planned articles parameter
     *
     * @param PlannedArticleParameter $parameter
     * @return array
     */
    public function getPlannedArticle(PlannedArticleParameter $parameter)
    {
        return array(
            'method' => 'GetPlannedArticle',
            'params' => array('getPlannedArticleParameter' => $parameter->getDataProperties())
        );
    }

    /**
     * Result gets planned articles result
     *
     * @param array $data
     * @return array
     */
    public function getPlannedArticleResult(array $data)
    {
        if (isset($data['GetPlannedArticleResult'])) {
            return $data['GetPlannedArticleResult'];
        }
        return array();
    }

    /**
     * Gets the planned articles parameter
     *
     * @param PlannedArticleParameter $parameter
     * @return array
     */
    public function getPlannedArticles(PlannedArticleParameter $parameter)
    {
        return array(
            'method' => 'GetPlannedArticles',
            'params' => array('getPlannedArticlesParameter' => $parameter->getDataProperties())
        );
    }

    /**
     * Gets the planned articles result
     *
     * @param $data
     * @return array
     */
    public function getPlannedArticlesResult(array $data)
    {
        if (isset($data['GetPlannedArticlesResult'])) {
            return $data['GetPlannedArticlesResult'];
        }
        return array();
    }

    /**
     * Gets the planned pictures parameter
     *
     * @todo
     */
    public function getPlannedPictures()
    {
        return array(
            'method' => 'GetPlannedPictures',
            'params' => array('getPlannedPicturesParameter')
        );
    }

    /**
     * Gets the planned pictures result
     *
     * @param array $data
     * @return array
     */
    public function getPlannedPicturesResult(array $data)
    {
        if (isset($data['GetPlannedPicturesResult']) && isset($data['GetPlannedPicturesResult']['Pictures'])) {
            return $data['GetPlannedPicturesResult']['Pictures'];
        }
        return array();
    }

    /**
     * Gets list of customer's listing packages(normally should be just one item) parameter
     *
     * @return array
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

    /**
     * Get Seller Package Result
     *
     * @param array $data
     * @return array
     */
    public function getSellerPackagesResult(array $data)
    {
        if (isset($data['GetSellerPackagesResult'])) {
            return $data['GetSellerPackagesResult'];
        }
        return array();
    }

    /**
     * Gets the sold article parameter
     *
     * @param $articleId
     * @return array
     */
    public function getSoldArticle($articleId)
    {
        return array(
            'method' => 'GetSoldArticle',
            'params' => array('getSoldArticleParameter' => array('ArticleId' => $articleId))
        );
    }

    /**
     * Get Sold Article Result
     *
     * @param array $data
     * @return array
     */
    public function getSoldArticleResult(array $data)
    {
        if (isset($data['GetSoldArticleResult'])) {
            return $data['GetSoldArticleResult'];
        }
        return array();
    }

    /**
     * Gets the sold articles parameter
     *
     * @param SoldArticlesParameter $parameter
     * @return array
     */
    public function getSoldArticles(SoldArticlesParameter $parameter)
    {
        return array(
            'method' => 'GetSoldArticles',
            'params' => array('getSoldArticlesParameter' => $parameter->getDataProperties())
        );
    }

    /**
     * Get Sold Articles Result
     *
     * @param array $data
     * @return array
     */
    public function getSoldArticlesResult(array $data)
    {
        if (isset($data['GetSoldArticlesResult']) && isset($data['GetSoldArticlesResult']['SoldArticles'])) {
            return $data['GetSoldArticlesResult'];
        }
        return array();
    }

    /**
     * Get available article templates parameter
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
     * Get the list of templates available Result
     *
     * @param array
     * @return array
     */
    public function getTemplatesResult(array $data)
    {
        if (isset($data['GetTemplatesResult']) && isset($data['GetTemplatesResult']['Templates'])) {
            return $data['GetTemplatesResult']['Templates'];
        }
        return array();
    }

    /**
     * Gets the unsold article parameter
     */
    public function getUnsoldArticle($articleId)
    {
        return array(
            'method' => 'GetUnsoldArticle',
            'params' => array('getUnsoldArticleParameter' => array('ArticleId' => $articleId))
        );
    }

    /**
     * Get Unsold Article Result
     *
     * @param array $data
     * @return array
     */
    public function getUnsoldArticleResult(array $data)
    {
        if (isset($data['GetUnsoldArticleResult'])) {
            return $data['GetUnsoldArticleResult'];
        }
        return array();
    }

    /**
     * Gets the unsold articles parameter
     *
     * @param UnsoldArticlesParameter $parameter
     * @return array
     */
    public function getUnsoldArticles(UnsoldArticlesParameter $parameter)
    {
        return array(
            'method' => 'GetUnsoldArticles',
            'params' => array('getUnsoldArticlesParameter' => $parameter->getDataProperties())
        );
    }

    /**
     * Get the unsold articles result
     *
     * @param array $data
     * @return array
     */
    public function getUnsoldArticlesResult(array $data)
    {
        if (isset($data['GetUnsoldArticlesResult'])) {
            return $data['GetUnsoldArticlesResult'];
        }
        return array();
    }

    /**
     * Inserts the answer.
     *
     * @todo
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
     *
     * @todo
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
     *
     * @todo
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
     *
     * @todo
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
     *
     * @todo
     */
    public function setPremiumPackageAutomaticReactivation()
    {
        return array(
            'method' => 'SetPremiumPackageAutomaticReactivation',
            'params' => array('SetPremiumPackageAutomaticReactivationParameter' => array())
        );
    }

    /**
     * Get In Transition Articles
     *
     * @param GetInTransitionArticlesParameter $parameter
     * @return array
     */
    public function getInTransitionArticles(GetInTransitionArticlesParameter $parameter)
    {
        return array(
            'method' => 'GetInTransitionArticles',
            'params' => array('GetInTransitionArticlesParameter' => $parameter->getDataProperties())
        );
    }

    /**
     * Get In Transition Articles Result
     *
     * @param array $data
     * @return array
     */
    public function getInTransitionArticlesResult(array $data)
    {
        if (isset($data['GetInTransitionArticlesResult'])) {
            return $data['GetInTransitionArticlesResult'];
        }
        return array();
    }
}
