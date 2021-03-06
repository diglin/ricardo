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

namespace Diglin\Ricardo\Managers;

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
 * @package Diglin\Ricardo\Managers
 */
class Sell extends ManagerAbstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'sell';

    /**
     * @var int
     */
    protected $_lastInsertArticle;

    /**
     * @param AddArticlePicturesParameter $parameter
     * @return float
     */
    public function addArticlePictures(AddArticlePicturesParameter $parameter)
    {
        $result = $this->_proceed('AddArticlePictures', $parameter);
        return $result['ArticleFee'];
    }

    /**
     * @param AppendArticleDescriptionParameter $parameter
     */
    public function appendArticleDescription(AppendArticleDescriptionParameter $parameter)
    {
        $this->_proceed('AppendArticleDescription', $parameter);
    }

    /**
     * @param CloseArticleParameter $parameter
     * @return array
     */
    public function closeArticle(CloseArticleParameter $parameter)
    {
        return $this->_proceed('CloseArticle', $parameter);
    }

    /**
     * @param CloseArticlesParameter $parameter
     * @return array
     */
    public function closeArticles(CloseArticlesParameter $parameter)
    {
        return $this->_proceed('CloseArticles', $parameter);
    }

    /**
     * @param DeletePlannedArticleParameter $parameter
     * @return array
     */
    public function deletePlannedArticle(DeletePlannedArticleParameter $parameter)
    {
        return $this->_proceed('DeletePlannedArticle', $parameter);
    }

    /**
     * @param DeletePlannedArticlesParameter $parameter
     * @return array
     */
    public function deletePlannedArticles(DeletePlannedArticlesParameter $parameter)
    {
        return $this->_proceed('DeletePlannedArticles', $parameter);
    }

    /**
     * @deprecated
     * @param InsertArticleParameter $parameter
     * @return array
     */
    public function insertArticle(InsertArticleParameter $parameter)
    {
        return $this->createArticle($parameter);
    }

    /**
     * @deprecated
     * @param InsertArticlesParameter $parameter
     * @return array
     */
    public function insertArticles(InsertArticlesParameter $parameter)
    {
        return $this->createArticles($parameter);
    }

    /**
     * @param InsertArticleParameter $parameter
     * @return array
     */
    public function createArticle(InsertArticleParameter $parameter)
    {
        return $this->_lastInsertArticle = $this->_proceed('CreateArticle', $parameter);
    }

    /**
     * @param InsertArticlesParameter $parameter
     * @return array
     */
    public function createArticles(InsertArticlesParameter $parameter)
    {
        return $this->_proceed('CreateArticles', $parameter);
    }

    /**
     * @return int
     */
    public function getLastInsertArticle()
    {
        return $this->_lastInsertArticle;
    }

    /**
     * @deprecated
     * @param $articleId
     * @return array
     */
    public function relistArticle($articleId)
    {
        return $this->republishArticle($articleId);
    }

    /**
     * @param $articleId
     * @return array
     * @throws \Diglin\Ricardo\Exceptions\ExceptionAbstract
     * @throws \Diglin\Ricardo\Exceptions\SecurityException
     * @throws \Exception
     */
    public function republishArticle($articleId)
    {
        $antiforgery = $this->getServiceManager()->getSecurityManager()->getAntiforgeryToken();
        return $this->_proceed('RepublishArticle', array('ArticleId' => $articleId, 'AntiforgeryToken' => $antiforgery));
    }

    /**
     * @deprecated
     * @param UpdateArticleParameter $parameter
     * @return float
     */
    public function updateArticle(UpdateArticleParameter $parameter)
    {
        return $this->modifyArticle($parameter);
    }

    /**
     * @param UpdateArticleParameter $parameter
     * @return mixed
     * @throws \Diglin\Ricardo\Exceptions\ExceptionAbstract
     * @throws \Diglin\Ricardo\Exceptions\SecurityException
     * @throws \Exception
     */
    public function modifyArticle(UpdateArticleParameter $parameter)
    {
        $result = $this->_proceed('ModifyArticle', $parameter);
        return $result['ArticleFee'];
    }

    /**
     * @param UpdateArticlePicturesParameter $parameter
     * @return $this
     * @throws \Diglin\Ricardo\Exceptions\ExceptionAbstract
     * @throws \Diglin\Ricardo\Exceptions\SecurityException
     * @throws \Exception
     */
    public function updateArticlePictures(UpdateArticlePicturesParameter $parameter)
    {
        $this->_proceed('UpdateArticlePictures', $parameter); // no value returned
        return $this;
    }

    /**
     * @param GetArticlesFeeParameter $parameter
     * @return $this
     * @throws \Diglin\Ricardo\Exceptions\ExceptionAbstract
     * @throws \Diglin\Ricardo\Exceptions\SecurityException
     * @throws \Exception
     * @return array
     */
    public function getArticlesFee(GetArticlesFeeParameter $parameter)
    {
        return $this->_proceed('ArticlesFee', $parameter);
    }

    /**
     * @param UpdateArticleBuyNowQuantityParameter $parameter
     * @return array
     * @throws \Diglin\Ricardo\Exceptions\ExceptionAbstract
     * @throws \Diglin\Ricardo\Exceptions\SecurityException
     * @throws \Exception
     */
    public function updateArticleBuyNowQuantity (UpdateArticleBuyNowQuantityParameter $parameter)
    {
        return $this->_proceed('UpdateArticleBuyNowQuantity', $parameter);
    }
}
