<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Services;

use Diglin\Ricardo\Managers\Sell\Parameter\AddArticlePicturesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\AppendArticleDescriptionParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticlePicturesParameter;

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
            'params' => array('AddArticlePicturesParameter' => $addArticlePicturesParameter->getDataProperties())
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
            'params' => array('AppendArticleDescriptionParameter' => $appendArticleDescriptionParameter->getDataProperties())
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
            'params' => array('CloseArticleParameter' => $closeArticleParameter->getDataProperties())
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
            'params' => array('CloseArticlesParameter' => $closeArticlesParameter->getDataProperties())
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
     * @param $deletePlannedArticleParameter
     */
    public function deletePlannedArticle($deletePlannedArticleParameter)
    {
    }

    /**
     * Deletes a list of planned articles.
     *
     * @param $deletePlannedArticlesParameter
     */
    public function deletePlannedArticles($deletePlannedArticlesParameter)
    {
    }

    /**
     * Gets the article fee.
     *
     * @param $getArticleFeeParameter
     */
    public function getArticleFee($getArticleFeeParameter)
    {
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
     * @param $insertArticleParameter
     * @return array
     */
    public function insertArticle(InsertArticlesParameter $insertArticleParameter)
    {
        return array(
            'method' => 'InsertArticle',
            'params' => array('InsertArticleParameter' => $insertArticleParameter->getDataProperties())
        );
    }

    /**
     * Get the article result data
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "InsertArticleResult": {
     *       "ArticleFee": "float"
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
    public function insertArticleResult(array $data)
    {
        if (isset($data['InsertArticleResult'])) {
            return $data['InsertArticleResult'];
        }
        return false;
    }

    /**
     * Inserts a list of articles or a planned articles. This method is currently not fully usable to external partners
     *
     * @param $insertArticlesParameter
     */
    public function insertArticles($insertArticlesParameter)
    {
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
     * @param $insertPreviewConvertedClassifiedParameter
     */
    public function insertPreviewConvertedClassified($insertPreviewConvertedClassifiedParameter)
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
     * Relists the article.
     *
     * @param $relistArticleParameter
     */
    public function relistArticle($relistArticleParameter)
    {
    }

    /**
     * Relists the auction articles. This method is currently not fully usable to external partners
     *
     * @param $relistArticlesParameter
     */
    public function relistArticles($relistArticlesParameter)
    {
    }

    /**
     * Relists the auction articles without modification. This method is currently not fully usable to external partners
     *
     * @param $relistArticlesWithoutModificationParameter
     */
    public function relistArticlesWithoutModification($relistArticlesWithoutModificationParameter)
    {
    }

    /**
     * Relists the article without modifing it.
     *
     * @param $relistArticleWithoutModificationParameter
     */
    public function relistArticleWithoutModification($relistArticleWithoutModificationParameter)
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
     * @param UpdateArticleParameter $updateArticleParameter
     * @return array
     */
    public function updateArticle(UpdateArticleParameter $updateArticleParameter)
    {
        return array(
            'method' => 'UpdateArticle',
            'params' => array('UpdateArticleParameter' => $updateArticleParameter->getDataProperties())
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
    public function updateArticleResult(array $data)
    {
        if (isset($data['UpdateArticleResult'])) {
            return $data['UpdateArticleResult'];
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
     * @param $updateArticleBuyNowQuantityParameter
     */
    public function updateArticleBuyNowQuantity($updateArticleBuyNowQuantityParameter)
    {
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
            'params' => array('UpdateArticlePicturesParameter' => $updateArticlePicturesParameter->getDataProperties())
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
     * Updates the planned article.
     *
     * @param $updatePlannedArticleParameter
     */
    public function updatePlannedArticle($updatePlannedArticleParameter)
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