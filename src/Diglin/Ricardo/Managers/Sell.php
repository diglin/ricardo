<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo\Managers;

use Diglin\Ricardo\Managers\Sell\Parameter\AddArticlePicturesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\AppendArticleDescriptionParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticlePicturesParameter;

class Sell extends ManagerAbstract
{
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
     * @param InsertArticlesParameter $parameter
     * @return array
     */
    public function insertArticle(InsertArticlesParameter $parameter)
    {
        return $this->_lastInsertArticle = $this->_proceed('InsertArticle', $parameter);
    }

    /**
     * @return int
     */
    public function getLastInsertArticle()
    {
        return $this->_lastInsertArticle;
    }

    /**
     * @param UpdateArticleParameter $parameter
     * @return float
     */
    public function updateArticle(UpdateArticleParameter $parameter)
    {
        $result = $this->_proceed('UpdateArticle', $parameter);
        return $result['ArticleFee'];
    }

    /**
     * @param UpdateArticlePicturesParameter $parameter
     * @return $this
     */
    public function updateArticlePictures(UpdateArticlePicturesParameter $parameter)
    {
        $this->_proceed('UpdateArticlePictures', $parameter); // no value returned
        return $this;
    }
}