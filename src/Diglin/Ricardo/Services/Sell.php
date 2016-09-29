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

use Diglin\Ricardo\Managers\Sell\Parameter\AddArticlePicturesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\AppendArticleDescriptionParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\DeletePlannedArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\DeletePlannedArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\GetArticlesFeeParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticleBuyNowQuantityParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticlePicturesParameter;

/**
 * Class Sell
 *
 * This service is to be used to manage your articles as a seller: you can list, relist, modify, close an article ...
 *
 * @package Diglin\Ricardo\Services
 * @link https://ws.ricardo.ch/RicardoApi/documentation/html/T_Ricardo_Contracts_ISellService.htm
 */
class Sell extends ServiceAbstract
{
    /**
     * @var string
     */
    protected $_service = 'SellService';

    /**
     * @var string
     */
    protected $_typeOfToken = self::TOKEN_TYPE_IDENTIFIED;

    /**
     * Adds the article pictures.
     *
     * @param AddArticlePicturesParameter $addArticlePicturesParameter
     * @return array
     */
    public function addArticlePictures(AddArticlePicturesParameter $addArticlePicturesParameter)
    {
        return array(
            'method' => 'AddArticlePictures',
            'params' => array('addArticlePicturesParameter' => $addArticlePicturesParameter->getDataProperties())
        );
    }

    /**
     * Get the article fee
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "AddArticlePicturesResult": {
     *       "ArticleFee": "float"
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return bool
     */
    public function addArticlePicturesResult($data)
    {
        if (isset($data['AddArticlePicturesResult'])) {
            return $data['AddArticlePicturesResult'];
        }

        return false;
    }

    /**
     * Appends the article description.
     *
     * @param AppendArticleDescriptionParameter $appendArticleDescriptionParameter
     * @return array
     */
    public function appendArticleDescription(AppendArticleDescriptionParameter $appendArticleDescriptionParameter)
    {
        return array(
            'method' => 'AppendArticleDescription',
            'params' => array('appendArticleDescriptionParameter' => $appendArticleDescriptionParameter->getDataProperties())
        );
    }

    /**
     * Nothing to return normally, we keep for errors or to have consistent API
     *
     * @param array $data
     * @return array
     */
    public function appendArticleDescriptionResult(array $data)
    {
        return $data;
    }

    /**
     * Closes the article.
     *
     * @param CloseArticleParameter $closeArticleParameter
     * @return array
     */
    public function closeArticle(CloseArticleParameter $closeArticleParameter)
    {
        return array(
            'method' => 'CloseArticle',
            'params' => array('closeArticleParameter' => $closeArticleParameter->getDataProperties())
        );
    }

    /**
     * Get the result of the closed article
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "CloseArticleResult": {
     *       "ArticleNr": "int"
     *       "IsClosed": "bool"
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array|bool
     */
    public function closeArticleResult(array $data)
    {
        if (isset($data['CloseArticleResult'])) {
            return $data['CloseArticleResult'];
        }

        return false;
    }

    /**
     * Closes a list of articles.
     *
     * @param CloseArticlesParameter $closeArticlesParameter
     * @return array
     */
    public function closeArticles(CloseArticlesParameter $closeArticlesParameter)
    {
        return array(
            'method' => 'CloseArticles',
            'params' => array('closeArticlesParameter' => $closeArticlesParameter->getDataProperties())
        );
    }

    /**
     * Get the result of the closed articles
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "CloseArticlesResult":
     *      "CloseArticleResults":
     *          [{
     *          "ArticleNr": "int"
     *          "IsClosed": "bool"
     *          },
     *          {
     *          "ArticleNr": "int"
     *          "IsClosed": "bool"
     *          }]
     *   }
     * </pre>
     *
     * @param array $data
     * @return array|bool
     */
    public function closeArticlesResult(array $data)
    {
        if (isset($data['CloseArticlesResult']) && isset($data['CloseArticlesResult']['CloseArticleResults'])) {
            return $data['CloseArticlesResult']['CloseArticleResults'];
        }

        return false;
    }

    /**
     * Closes the classified.
     *
     * @param $closeClassifiedParameter
     */
    public function closeClassified($closeClassifiedParameter)
    {
    }

    /**
     * Closes list of classified.
     *
     * @param $closeClassifiedsParameter
     */
    public function closeClassifieds($closeClassifiedsParameter)
    {
    }

    /**
     * Deletes the planned article.
     *
     * @param DeletePlannedArticleParameter $deletePlannedArticleParameter
     * @return array
     */
    public function deletePlannedArticle(DeletePlannedArticleParameter $deletePlannedArticleParameter)
    {
        return array(
            'method' => 'DeletePlannedArticle',
            'params' => array('deletePlannedArticleParameter' => $deletePlannedArticleParameter->getDataProperties())
        );

    }

    /**
     * Get the result of the deleted article
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "DeletePlannedArticleResult": {
     *       "PlannedArticleId": "int"
     *       "PlannedIndex": "int"
     *       "IsClosed": "bool"
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array|bool
     */
    public function deletePlannedArticleResult(array $data)
    {
        if (isset($data['DeletePlannedArticleResult'])) {
            return $data['DeletePlannedArticleResult'];
        }

        return false;
    }

    /**
     * Deletes the planned articles.
     *
     * @param DeletePlannedArticlesParameter $deletePlannedArticleParameter
     * @return array
     */
    public function deletePlannedArticles(DeletePlannedArticlesParameter $deletePlannedArticleParameter)
    {
        return array(
            'method' => 'DeletePlannedArticles',
            'params' => array('deletePlannedArticlesParameter' => $deletePlannedArticleParameter->getDataProperties())
        );
    }

    /**
     * Get the result of the deleted articles
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "DeletePlannedArticlesResult":
     *       "DeleteResults": [{
     *           "PlannedArticleId": "int"
     *           "PlannedIndex": "int"
     *          "IsClosed": "bool"
     *          }]
     *   }
     * </pre>
     *
     * @param array $data
     * @return array|bool
     */
    public function deletePlannedArticlesResult(array $data)
    {
        if (isset($data['DeletePlannedArticlesResult']) && isset($data['DeletePlannedArticlesResult']['DeleteResults'])) {
            return $data['DeletePlannedArticlesResult']['DeleteResults'];
        }

        return false;
    }

    /**
     * Gets the article fee.
     *
     * @param $getArticleFeeParameter
     * @return array
     */
    public function getArticleFee($getArticleFeeParameter)
    {
    }

    public function getArticleFeeResult($getArticleFeeParameter)
    {
    }

    /**
     * @param GetArticlesFeeParameter $getArticlesFeeParameter
     * @return array
     */
    public function getArticlesFee(GetArticlesFeeParameter $getArticlesFeeParameter)
    {
        return array(
            'method' => 'GetArticlesFee',
            'params' => array('getArticlesFeeParameter' => $getArticlesFeeParameter->getDataProperties())
        );
    }

    /**
     * @param array $data
     * @return bool
     *
     * [0] => Array
     * (
     * [CoveredByPLP] =>
     * [ListingFee] => 0
     * [PromotionFees] => Array
     * (
     * [0] => Array
     * (
     * [PromotionFee] => 0
     * [PromotionId] => 4194304
     * )
     *
     * [1] => Array
     * (
     * [PromotionFee] => 0
     * [PromotionId] => 8388608
     * )
     * [...] => ...
     *
     * )
     *
     * [TotalFee] => 5
     * )
     */
    public function getArticlesFeeResult(array $data)
    {
        if (isset($data['GetArticlesFeeResult']) && isset($data['GetArticlesFeeResult']['ArticlesFee'])) {
            return $data['GetArticlesFeeResult']['ArticlesFee'];
        }

        return false;
    }

    /**
     * Gets the update article fee.
     *
     * @param $getUpdateArticleFeeParameter
     */
    public function getUpdateArticleFee($getUpdateArticleFeeParameter)
    {
    }

    /**
     * Gets the update classified fee.
     *
     * @param $getUpdateClassifiedFeeParameter
     */
    public function getUpdateClassifiedFee($getUpdateClassifiedFeeParameter)
    {
    }

    /**
     * Inserts an article or a planned article
     *
     * @deprecated
     * @param $insertArticleParameter
     * @return array
     */
    public function insertArticle(InsertArticleParameter $insertArticleParameter)
    {
        return $this->createArticle($insertArticleParameter);
    }

    /**
     * Get the article result data
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "InsertArticleResult": {
     *       "ArticleFee": [{
     *          "ListingFee": "float"
     *          "TotalFee": "float"
     *          "PromotionFees": [{
     *              "PromotionFee": "float"
     *              "PromotionId":  "int"
     *          }]
     *        }]
     *       "ArticleId": "int"
     *       "CarDealerArticleId": "int"
     *       "ErrorCodes": "int" ArticleErrors
     *       "PlannedArticleId": "int"
     *     }
     *   }
     * </pre>
     *
     * @deprecated
     * @param array $data
     * @return array|bool
     */
    public function insertArticleResult(array $data)
    {
        return $this->createArticleResult($data);
    }

    /**
     * Inserts an article or a planned article
     *
     * @param $insertArticleParameter
     * @return array
     */
    public function createArticle(InsertArticleParameter $insertArticleParameter)
    {
        return array(
            'method' => 'CreateArticle',
            'params' => array('insertArticleParameter' => $insertArticleParameter->getDataProperties())
        );
    }

    /**
     * Get the article result data
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "CreateArticleResult": {
     *       "ArticleFee": [{
     *          "ListingFee": "float"
     *          "TotalFee": "float"
     *          "PromotionFees": [{
     *              "PromotionFee": "float"
     *              "PromotionId":  "int"
     *          }]
     *        }]
     *       "ArticleId": "int"
     *       "CarDealerArticleId": "int"
     *       "ErrorCodes": "int" ArticleErrors
     *       "PlannedArticleId": "int"
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array|bool
     */
    public function createArticleResult(array $data)
    {
        if (isset($data['CreateArticleResult'])) {
            return $data['CreateArticleResult'];
        }

        return false;
    }

    /**
     * Inserts a list of articles or a planned articles. This method is currently not fully usable to external partners
     *
     * @deprecated
     * @param InsertArticlesParameter $insertArticlesParameter
     * @return array
     */
    public function insertArticles(InsertArticlesParameter $insertArticlesParameter)
    {
        return $this->createArticles($insertArticlesParameter);
    }

    /**
     * Get the article result data
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "CreateArticlesResult": {
     *      "Results": [{
     *          "ArticleFee": "float"
     *          "ArticleId": "int"
     *          "CarDealerArticleId": "int"
     *          "CorrelationKey": "int"
     *          "ErrorCodes": "int" ArticleErrors
     *          "ErrorCodesType": "int" @see https://ws.ricardo.ch/RicardoApi/documentation/html/T_Ricardo_Enums_Errors_ErrorList.htm
     *          "PlannedArticleId": "int"
     *      }]
     *   }
     * </pre>
     *
     * @deprecated
     * @param array $data
     * @return array|bool
     */
    public function insertArticlesResult(array $data)
    {
        return $this->createArticlesResult($data);
    }

    /**
     * Inserts a list of articles or a planned articles. This method is currently not fully usable to external partners
     *
     * @param InsertArticlesParameter $insertArticlesParameter
     * @return array
     */
    public function createArticles(InsertArticlesParameter $insertArticlesParameter)
    {
        return array(
            'method' => 'CreateArticles',
            'params' => array('insertArticlesParameter' => $insertArticlesParameter->getDataProperties())
        );
    }

    /**
     * Get the article result data
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "CreateArticlesResult": {
     *      "Results": [{
     *          "ArticleFee": "float"
     *          "ArticleId": "int"
     *          "CarDealerArticleId": "int"
     *          "CorrelationKey": "int"
     *          "ErrorCodes": "int" ArticleErrors
     *          "ErrorCodesType": "int" @see https://ws.ricardo.ch/RicardoApi/documentation/html/T_Ricardo_Enums_Errors_ErrorList.htm
     *          "PlannedArticleId": "int"
     *      }]
     *   }
     * </pre>
     *
     * @param array $data
     * @return array|bool
     */
    public function createArticlesResult(array $data)
    {
        if (isset($data['CreateArticlesResult']) && isset($data['CreateArticlesResult']['Results'])) {
            return $data['CreateArticlesResult']['Results'];
        }

        return false;
    }

    /**
     * Inserts the classified.
     *
     * @param $insertClassifiedParameter
     */
    public function insertClassified($insertClassifiedParameter)
    {
    }

    /**
     * Insert a list of classified items. This method is currently not fully usable to external partners
     *
     * @param $insertClassifiedsParameter
     */
    public function insertClassifieds($insertClassifiedsParameter)
    {
    }

    /**
     * Inserts the preview converted classified.
     *
     * @deprecated
     * @param $insertPreviewConvertedClassifiedParameter
     */
    public function insertPreviewConvertedClassified($insertPreviewConvertedClassifiedParameter)
    {
        return $this->createPreviewConvertedClassified($insertPreviewConvertedClassifiedParameter);
    }

    /**
     * Inserts the preview converted classified.
     *
     * @param $insertPreviewConvertedClassifiedParameter
     */
    public function createPreviewConvertedClassified($insertPreviewConvertedClassifiedParameter)
    {
    }

    /**
     * Ocrs recognition.
     *
     * @param $ocrRecognizesParameter
     */
    public function ocrRecognize($ocrRecognizesParameter)
    {
    }

    /**
     * @deprecated
     * @param $parameters
     * @return array
     */
    public function relistArticle($parameters)
    {
        return $this->republishArticle($parameters);
    }

    /**
     * Relists the auction articles. This method is currently not fully usable to external partners
     *
     * @deprecated
     * @param array $articleIds
     * @return array
     */
    public function relistArticles($articleIds)
    {
        return $this->republishArticles($articleIds);
    }

    /**
     * @param $parameters
     * @return array
     */
    public function republishArticle($parameters)
    {
        $articleId = $parameters['ArticleId'];
        $antiforgeryToken = $parameters['AntiforgeryToken'];

        return array(
            'method' => 'RepublishArticle',
            'params' => array('relistArticleParameter' => array('ArticleId', $articleId, 'AntiforgeryToken' => $antiforgeryToken))
        );
    }

    /**
     * @param array $data
     * @return bool
     */
    public function republishArticleResult(array $data)
    {
        if (isset($data['RepublishArticleResult'])) {
            return $data['RepublishArticleResult'];
        }

        return false;
    }

    /**
     * Relists the auction articles. This method is currently not fully usable to external partners
     *
     * @param array $articleIds
     * @return array
     */
    public function republishArticles($articleIds)
    {
        return array(
            'method' => 'RepublishArticles',
            'params' => array('relistArticlesParameter' => array('ArticleId', $articleIds))
        );
    }

    /**
     * @param array $data
     * @return bool
     */
    public function republishArticlesResult(array $data)
    {
        if (isset($data['RepublishArticlesResult'])) {
            return $data['RepublishArticlesResult'];
        }

        return false;
    }

    /**
     * Relists the auction articles without modification. This method is currently not fully usable to external partners
     *
     * @deprecated
     * @param $relistArticlesWithoutModificationParameter
     */
    public function relistArticlesWithoutModification($relistArticlesWithoutModificationParameter)
    {
        return $this->republishArticleWithoutModification($relistArticlesWithoutModificationParameter);
    }

    /**
     * Relists the article without modifing it.
     *
     * @param $relistArticleWithoutModificationParameter
     */
    public function relistArticleWithoutModification($relistArticleWithoutModificationParameter)
    {
        return $this->republishArticlesWithoutModification($relistArticleWithoutModificationParameter);
    }

    /**
     * @param $relistArticlesWithoutModificationParameter
     */
    public function republishArticleWithoutModification($relistArticlesWithoutModificationParameter)
    {
    }

    /**
     * @param $relistArticleWithoutModificationParameter
     */
    public function republishArticlesWithoutModification($relistArticleWithoutModificationParameter)
    {
    }

    /**
     * Relists the classified.
     *
     * @param $relistClassifiedParameter
     */
    public function relistClassified($relistClassifiedParameter)
    {
    }

    /**
     * Relists the classified articles. This method is currently not fully usable to external partners
     *
     * @param $relistClassifiedsParameter
     */
    public function relistClassifieds($relistClassifiedsParameter)
    {
    }

    /**
     * Relists the classified article without modification. This method is currently not fully usable to external partners
     *
     * @param $relistClassifiedsWithoutModificationParameter
     */
    public function relistClassifiedsWithoutModification($relistClassifiedsWithoutModificationParameter)
    {
    }

    /**
     * Relists the classified Without Modification.
     *
     * @param $relistClassifiedWithoutModificationParameter
     */
    public function relistClassifiedWithoutModification($relistClassifiedWithoutModificationParameter)
    {
    }

    /**
     * Ocrs recognition.
     *
     * @param $ocrRecognizesParameter
     */
    public function tesseractRecognize($ocrRecognizesParameter)
    {
    }

    /**
     * Updates the article.
     *
     * @deprecated
     * @param UpdateArticleParameter $updateArticleParameter
     * @return array
     */
    public function updateArticle(UpdateArticleParameter $updateArticleParameter)
    {
        return $this->modifyArticle($updateArticleParameter);
    }

    /**
     * Get the article result data
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "UpdateArticleResult": {
     *       "ArticleFee": "float"
     *     }
     *   }
     * </pre>
     *
     * @deprecated
     * @param array $data
     * @return bool|array
     */
    public function updateArticleResult(array $data)
    {
        return $this->modifyArticleResult($data);
    }

    /**
     * Updates the article.
     *
     * @param UpdateArticleParameter $updateArticleParameter
     * @return array
     */
    public function modifyArticle(UpdateArticleParameter $updateArticleParameter)
    {
        return array(
            'method' => 'ModifyArticle',
            'params' => array('updateArticleParameter' => $updateArticleParameter->getDataProperties())
        );
    }

    /**
     * Get the article result data
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "UpdateArticleResult": {
     *       "ArticleFee": "float"
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return bool|array
     */
    public function modifyArticleResult(array $data)
    {
        if (isset($data['ModifyArticleResult'])) {
            return $data['ModifyArticleResult'];
        }

        return false;
    }

    /**
     * Updates the article buy now price.
     *
     * @param $updateArticleBuyNowPriceParameter
     */
    public function updateArticleBuyNowPrice($updateArticleBuyNowPriceParameter)
    {
    }

    /**
     * Updates the article buy now quantity.
     *
     * @param UpdateArticleBuyNowQuantityParameter $updateArticleBuyNowQuantityParameter
     * @return array
     */
    public function updateArticleBuyNowQuantity(UpdateArticleBuyNowQuantityParameter $updateArticleBuyNowQuantityParameter)
    {
        return array(
            'method' => 'UpdateArticleBuyNowQuantity',
            'params' => array('updateArticleBuyNowQuantityParameter' => $updateArticleBuyNowQuantityParameter->getDataProperties())
        );
    }

    /**
     * Get the pudate article buy now quantity result data
     *
     * @return bool
     */
    public function updateArticleBuyNowQuantityResult()
    {
        if (isset($data['UpdateArticleBuyNowQuantityResult'])) {
            return $data['UpdateArticleBuyNowQuantityResult'];
        }
        return false;
    }

    /**
     * Updates the article buy now relist count.
     *
     * @param $updateArticleBuyNowRelistCountParameter
     */
    public function updateArticleBuyNowRelistCount($updateArticleBuyNowRelistCountParameter)
    {
    }

    /**
     * Updates the article pictures.
     *
     * @param UpdateArticlePicturesParameter $updateArticlePicturesParameter
     * @return array
     */
    public function updateArticlePictures(UpdateArticlePicturesParameter $updateArticlePicturesParameter)
    {
        return array(
            'method' => 'UpdateArticlePictures',
            'params' => array('updateArticlePicturesParameter' => $updateArticlePicturesParameter->getDataProperties())
        );
    }

    /**
     * Nothing to return normally, we keep for errors or to have consistent API
     *
     * @param $data
     * @return array
     */
    public function updateArticlePicturesResult($data)
    {
        return $data;
    }

    /**
     * Updates the classified.
     *
     * @param $updateClassifiedParameter
     */
    public function updateClassified($updateClassifiedParameter)
    {
    }

    /**
     * Update classified pictures'
     *
     * @param $updateClassifiedPicturesParameter
     */
    public function updateClassifiedPictures($updateClassifiedPicturesParameter)
    {
    }

    /**
     * Update the planned article.
     *
     * @deprecated
     * @param $updatePlannedArticleParameter
     */
    public function updatePlannedArticle($updatePlannedArticleParameter)
    {
        return $this->modifyPlannedArticle($updatePlannedArticleParameter);
    }

    /**
     * Modify/Update the planned article.
     *
     * @param $updatePlannedArticleParameter
     */
    public function modifyPlannedArticle($updatePlannedArticleParameter)
    {
    }

    /**
     * Upd
     *
     * @param $updatePlannedArticlePicturesParameter
     */
    public function updatePlannedArticlePictures($updatePlannedArticlePicturesParameter)
    {
    }
}
