<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Diglin\Ricardo\Managers;

use Diglin\Ricardo\Managers\Sell\Parameter\AddArticlePicturesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\AppendArticleDescriptionParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\DeletePlannedArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\DeletePlannedArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\UpdateArticlePicturesParameter;

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
     * @param InsertArticleParameter $parameter
     * @return array
     */
    public function insertArticle(InsertArticleParameter $parameter)
    {
        return $this->_lastInsertArticle = $this->_proceed('InsertArticle', $parameter);
    }

    /**
     * @param InsertArticlesParameter $parameter
     * @return array
     */
    public function insertArticles(InsertArticlesParameter $parameter)
    {
        return $this->_proceed('InsertArticles', $parameter);
    }

    /**
     * @return int
     */
    public function getLastInsertArticle()
    {
        return $this->_lastInsertArticle;
    }

    /**
     * @param $articleId
     * @return array
     */
    public function relistArticle($articleId)
    {
        $antiforgery = $this->getServiceManager()->getSecurityManager()->getAntiforgeryToken();
        return $this->_proceed('RelistArticle', array('ArticleId' => $articleId, 'AntiforgeryToken' => $antiforgery));
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