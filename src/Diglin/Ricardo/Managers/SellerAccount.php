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

use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\ArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\ClosedArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\OpenArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\PlannedArticleParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\PlannedArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\SoldArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\UnsoldArticlesParameter;

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
        return (bool) $this->_proceed('AssertArticleModification', $articleId);
    }

    /**
     * Get Article Information for NOT planned article
     *
     * @param string $articleId
     * @return array
     */
    public function getArticle($articleId)
    {
        return $this->_proceed('Article', $articleId);
    }

    /**
     * Get all auctions by date and type
     *
     * @param ArticlesParameter $parameter
     * @return array
     */
    public function getArticles(ArticlesParameter $parameter)
    {
        return $this->_proceed('Articles', $parameter);
    }

    /**
     * @param ClosedArticlesParameter $parameter
     * @return array
     */
    public function getClosedArticles(ClosedArticlesParameter $parameter)
    {
        return $this->_proceed('ClosedArticles', $parameter);
    }

    /**
     * Gets an open article.
     */
    public function getOpenArticle($articleId)
    {
        return $this->_proceed('OpenArticle', $articleId);
    }

    /**
     * Gets the open articles.
     *
     * @param OpenArticlesParameter $parameter
     * @return array
     */
    public function getOpenArticles(OpenArticlesParameter $parameter)
    {
        return $this->_proceed('OpenArticles', $parameter);
    }


    /**
     * Gets the payment options for a seller.
     *
     * @param int $customerId
     * @return array
     */
    public function getPaymentOptions($customerId = null)
    {
        if (is_null($customerId)) {
            $customer = new Customer($this->getServiceManager());
            $customerInfo = $customer->getCustomerInformation();
            if (isset($customerInfo['CustomerId'])) {
                $customerId = $customerInfo['CustomerId'];
            }
        }

        return $this->_proceed('PaymentOptions', $customerId);

    }

    /**
     * Gets a planned articles
     *
     * @param PlannedArticleParameter $parameter
     * @return array
     */
    public function getPlannedArticle(PlannedArticleParameter $parameter)
    {
        return $this->_proceed('PlannedArticle', $parameter);
    }

    /**
     * Gets the planned articles.
     *
     * @param PlannedArticlesParameter $parameter
     * @return array
     */
    public function getPlannedArticles(PlannedArticlesParameter $parameter)
    {
        return $this->_proceed('PlannedArticles', $parameter);
    }

    /**
     * Gets the planned pictures.
     */
    public function getPlannedPictures()
    {
        // @todo
    }

    /**
     * Gets list of customer's listing packages(normally should be just one item)
     *
     * @return array
     */
    public function getSellerPackages()
    {
        return $this->_proceed('SellerPackages');
    }

    /**
     * Gets the sold article.
     *
     * @param int $articleId
     * @return array
     */
    public function getSoldArticle($articleId)
    {
        return $this->_proceed('SoldArticle', $articleId);
    }

    /**
     * @param SoldArticlesParameter $parameter
     * @return array
     */
    public function getSoldArticles(SoldArticlesParameter $parameter)
    {
        return $this->_proceed('SoldArticles', $parameter);
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
    public function getUnsoldArticle($articleId)
    {
        return $this->_proceed('UnsoldArticle', $articleId);
    }

    /**
     * Gets the unsold articles
     *
     * @param UnsoldArticlesParameter $parameter
     * @return array
     */
    public function getUnsoldArticles(UnsoldArticlesParameter $parameter)
    {
        return $this->_proceed('UnsoldArticles', $parameter);
    }

    /**
     * Inserts the answer.
     */
    public function insertAnswer()
    {
        // @todo
    }

    /**
     * Inserts selected by customer listing package
     */
    public function insertSellerPackage()
    {
        // @todo
    }

    /**
     * Removes the card payment option from specified articles.
     */
    public function removeCardPaymentOption()
    {
        // @todo
    }

    /**
     * Sets if the article has cumulative shipping.
     */
    public function setCumulativeShipping()
    {
        // @todo
    }

    /**
     * Change the automatic reactivation for a premium package
     */
    public function setPremiumPackageAutomaticReactivation()
    {
        // @todo
    }
}
