<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Managers;

/**
 * Class SellerAccount
 * @package Diglin\Ricardo\Managers
 */
class SellerAccount extends ManagerAbstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'seller_account';

    /**
     * @var array
     */
    protected $_templates;

    /**
     * Add Card payment options to one or more articles
     *
     * @param array $articleIds
     * @return array
     */
    public function addCardPaymentOption(array $articleIds)
    {
        return $this->_proceed('AddCardPaymentOption', array('article_ids' => $articleIds));
    }

    /**
     * Get if an article is allowed to be modified or not
     *
     * @param int $articleId
     * @return bool
     */
    public function getArticleModificationAllowed($articleId)
    {
        return (bool)$this->_proceed('AssertArticleModification', array('article_id' => $articleId));
    }

    /**
     * Get Article Information
     *
     * @param string $articleId
     * @return array
     */
    public function getArticle($articleId)
    {
        return $this->_proceed('Article', array('article_id' => $articleId));
    }

    /**
     * Get all auctions by date and type
     *
     * @param int $articleTypes
     * @param int $closeStatus
     * @param bool $isPlannedArticles
     * @param string $lastModificationDate
     * @return array
     */
    public function getArticles($articleTypes, $closeStatus, $isPlannedArticles = false, $lastModificationDate = null)
    {
        return $this->_proceed('Articles', array(
            'article_types' => $articleTypes,
            'close_status' => $closeStatus,
            'is_planned_articles' => $isPlannedArticles,
            'last_modification_date' => $lastModificationDate
        ));
    }

    /**
     * Gets a classified.
     */
    public function getClassified()
    {
    }

    /**
     * Get all classifieds by date and type
     */
    public function getClassifieds()
    {
    }


    /**
     * Get all articles that were closed by customer
     */
    public function getClosedArticles()
    {
    }


    /**
     * Get all classified items that were closed by customer
     */
    public function getClosedClassifieds()
    {
    }


    /**
     * Gets an open article.
     */
    public function getOpenArticle()
    {
    }


    /**
     * Gets the open articles.
     */
    public function getOpenArticles()
    {
    }


    /**
     * Gets the payment options for a seller.
     */
    public function getPaymentOptions()
    {
    }

    /**
     * Gets a planned articles.
     */
    public function getPlannedArticle()
    {
    }

    /**
     * Gets the planned articles.
     */
    public function getPlannedArticles()
    {
    }

    /**
     * Gets the planned pictures.
     */
    public function getPlannedPictures()
    {
    }

    /**
     * Gets list of customer's listing packages(normally should be just one item)
     */
    public function getSellerPackages()
    {
    }

    /**
     * Gets the sold article.
     */
    public function getSoldArticle()
    {
    }

    public function getSoldArticles()
    {
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        if (empty($this->_templates)) {
            $this->_templates = $this->_proceed('Templates');
        }
        return $this->_templates;
    }

    /**
     * Gets the unsold article.
     */
    public function getUnsoldArticle()
    {
    }

    /**
     * Gets the unsold articles.
     */
    public function getUnsoldArticles()
    {
    }

    /**
     * Inserts the answer.
     */
    public function insertAnswer()
    {
    }

    /**
     * Inserts selected by customer listing package
     */
    public function insertSellerPackage()
    {
    }

    /**
     * Removes the card payment option from specified articles.
     */
    public function removeCardPaymentOption()
    {
    }

    /**
     * Sets if the article has cumulative shipping.
     */
    public function setCumulativeShipping()
    {
    }

    /**
     * Change the automatic reactivation for a premium package
     */
    public function setPremiumPackageAutomaticReactivation()
    {
    }
}