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

use Diglin\Ricardo\Core\Helper;
use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Enums\Article\CloseListStatus;
use Diglin\Ricardo\Enums\Customer\ArticleTypeFilter;
use Diglin\Ricardo\Enums\Customer\TransitionStatus;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ClosePlannedArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\DeletePlannedArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\ArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\ClosedArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\GetInTransitionArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\OpenArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\SoldArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\UnsoldArticlesParameter;

/**
 * Class SellerAccountTest
 * @package Diglin\Ricardo\Managers
 */
class SellerAccountTest extends TestAbstract
{
    /**
     * @var SellerAccount
     */
    protected $_sellerAccountManager;

    /**
     * @var int
     */
    protected $_articleId;

    protected function setUp()
    {
        $this->_sellerAccountManager = new SellerAccount($this->getServiceManager());

        //@todo add a template

        parent::setUp();
    }

    protected function tearDown()
    {
        //@todo remove the template

        parent::tearDown();
    }


    public function testAddCardPaymentOption()
    {
        //@todo provide an article id or more to do the test

        //$result = $this->_sellerAccountManager->addCardPaymentOption(array());
    }

    public function testGetArticleModificationAllowed()
    {
        //@todo provide an article id to do the test

        //$result = $this->_sellerAccountManager->getArticleModificationAllowed(0);
    }

    public function testGetArticle()
    {
        $sell = new Sell($this->getServiceManager());
        $result = $sell->insertArticle($this->getArticle(false, true, true));
        $articleId = $result['ArticleId'];

        $result = $this->_sellerAccountManager->getArticle($articleId);

        parent::outputContent($result, 'Get Article: ');

        $this->assertArrayHasKey('ArticleInformation', $result, 'The article ' . $articleId . ' has no article information');
        $this->assertArrayHasKey('BidInformation', $result, 'The article ' . $articleId . ' has bid information');
        $this->assertArrayHasKey('Deliveries', $result, 'The article ' . $articleId . ' has no delivery');
        $this->assertArrayHasKey('Descriptions', $result, 'The article ' . $articleId . ' has no description');
        $this->assertArrayHasKey('Pictures', $result, 'The article ' . $articleId . ' has no picture');

        $closeParameter = new CloseArticleParameter();
        $closeParameter
            ->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken())
            ->setArticleId($articleId);

        $sell->closeArticle($closeParameter);
    }

    public function testGetArticles()
    {
        $articles = array();

        $sell = new Sell($this->getServiceManager());

        for ($i = 0; $i < 2; $i++) {
            $result = $sell->insertArticle($this->getArticle(false, true, true));
            $articles[] = $result['ArticleId'];
        }

        $articleParameter = new ArticlesParameter();
        $articleParameter
            ->setArticlesType(ArticlesTypes::ALL)
            ->setCloseStatus(CloseListStatus::OPEN);

        $result = $this->_sellerAccountManager->getArticles($articleParameter);

        parent::outputContent($result, 'Get Articles: ');

        $this->assertGreaterThanOrEqual(1, count($result), 'No result found');
        $this->assertArrayHasKey('ArticleId', $result[0], 'No article ID found');
        $this->assertArrayHasKey('ArticleInformationLimit', $result[0], 'Get Articles has no article information limit');

        $closesParameter = new CloseArticlesParameter();
        $closesParameter->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken());

        foreach ($articles as $article) {
            $closesParameter->setArticleIds($article);
        }

        $sell->closeArticles($closesParameter);
    }

    public function testCloseArticles()
    {
        $articles = array();
        $totalItems = 2;

        $sell = new Sell($this->getServiceManager());

        for ($i = 0; $i < $totalItems; $i++) {
            $result = $sell->insertArticle($this->getArticle(false, true, true));
            $articles[] = $result['ArticleId'];
        }

        $closesParameter = new CloseArticlesParameter();
        $closesParameter->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken());

        foreach ($articles as $article) {
            $closesParameter->setArticleIds($article);
        }

        $results = $sell->closeArticles($closesParameter);

        parent::outputContent($results, 'Closed Articles: ');

        foreach ($results as $result) {
            $this->assertGreaterThanOrEqual($totalItems, count($result), 'No result found');
            $this->assertArrayHasKey('ArticleNr', $result, 'No article ID found');
            $this->assertArrayHasKey('IsClosed', $result, 'Article has no close information');
        }

        $closesParameter = new CloseArticlesParameter();
        $closesParameter->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken());

        foreach ($articles as $article) {
            $closesParameter->setArticleIds($article);
        }

        $results = $sell->closeArticles($closesParameter);

        parent::outputContent($results, 'Closed Articles: ');

        foreach ($results as $result) {
            $this->assertArrayHasKey('IsClosed', $result, 'IsClosed Key not found');
            $this->assertTrue((bool) $result['IsClosed'], 'Article is still open');
        }
    }

    public function testDeletePlannedArticles()
    {
        $articles = array();
        $totalItems = 2;

        $sell = new Sell($this->getServiceManager());

        for ($i = 0; $i < $totalItems; $i++) {
            $result = $sell->insertArticle($this->getArticle(false, true, false));
            $articles[] = $result['ArticleId'];
        }

        $deletePlannedArticlesParameter = new DeletePlannedArticlesParameter();
        $deletePlannedArticlesParameter->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken());

        foreach ($articles as $article) {
            $closePlanned = new ClosePlannedArticleParameter();
            $closePlanned->setPlannedArticleId($article);
            $deletePlannedArticlesParameter->setArticles($closePlanned);
        }

        $results = $sell->deletePlannedArticles($deletePlannedArticlesParameter);

        parent::outputContent($results, 'Delete Planned Articles: ');

        foreach ($results as $result) {
            $this->assertGreaterThanOrEqual($totalItems, count($result), 'No result found');
            $this->assertArrayHasKey('PlannedArticleId', $result, 'PlannedArticleId not found');
            $this->assertArrayHasKey('IsClosed', $result, 'IsClosed Key not found');
        }

        foreach ($articles as $article) {
            $closePlanned = new ClosePlannedArticleParameter();
            $closePlanned->setPlannedArticleId($article);
            $deletePlannedArticlesParameter->setArticles($closePlanned);
        }

        $deletePlannedArticlesParameter->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken());
        $results = $sell->deletePlannedArticles($deletePlannedArticlesParameter);

        parent::outputContent($results, 'Delete Planned Articles: ');

        foreach ($results as $result) {
            $this->assertArrayHasKey('PlannedArticleId', $result, 'PlannedArticleId Key not found');
            $this->assertArrayHasKey('IsClosed', $result, 'IsClosed Key not found');
        }
    }

    public function testGetTemplates()
    {
        $result = $this->_sellerAccountManager->getTemplates();

        parent::outputContent($result, 'Get Templates: ');

        $this->assertGreaterThanOrEqual(1, count($result), 'Number of templates found is not greater than 1. Create one on ricardo.ch or the sandbox, not into the ricardo Assistant');
    }

    public function testGetSellerPackages()
    {
        $result = $this->_sellerAccountManager->getSellerPackages();

        parent::outputContent($result, 'Get Seller Packages: ');
    }

    public function testGetPaymentOptions()
    {
        $customerId = null;
        $customer = new Customer($this->getServiceManager());
        $customerInfo = $customer->getCustomerInformation();

        if(isset($customerInfo['CustomerId'])) {
            $customerId = $customerInfo['CustomerId'];
        }

        $result = $this->_sellerAccountManager->getPaymentOptions($customerId);

        parent::outputContent($result, 'Get Payment Options: ');
    }

    public function testGetClosedArticles()
    {
        $closedParameter = new ClosedArticlesParameter();

        $result = $this->_sellerAccountManager->getClosedArticles($closedParameter);

        parent::outputContent($result, 'Get Closed Articles: ');
    }

    /**
     * @todo difficult to test it, help to debug but lots of things must be done manually
     */
    public function testGetSoldArticle()
    {
//        $articleId = 1234;
//        $result = $this->_sellerAccountManager->getSoldArticle($articleId);
//
//
//        parent::outputContent($result, 'Get Sold Article: ');
    }

    public function testGetSoldArticles()
    {
        $articleParameter = new SoldArticlesParameter();
        $articleParameter
            ->setArticleTitleFilter(null)
            ->setArticleTypeFilter(ArticlesTypes::ALL)
            ->setInternalReferenceFilter(null)
            ->setLastnameFilter(null)
            ->setNicknameFilter(null)
            ->setIsCompletedTransaction(false)
            ->setPageSize(5);

        $result = $this->_sellerAccountManager->getSoldArticles($articleParameter);

        parent::outputContent($result, 'Get Sold Articles: ', true);

        $this->assertArrayHasKey('TotalLines', $result, 'Total Lines result is not returned');
        $this->assertArrayHasKey('SoldArticles', $result, 'Sold Articles result is not returned');

    }

    public function testGetUnsoldArticles()
    {
        $unsoldArticlesParameter = new UnsoldArticlesParameter();
        $unsoldArticlesParameter
            ->setArticleTitleFilter(null)
            ->setArticleTypeFilter(ArticlesTypes::ALL)
            ->setInternalReferenceFilter(null)
            ->setMinimumEndDate(Helper::getJsonDate(time() - (14 * 24 * 60 * 60)))
            ->setPageSize(5);

        $result = $this->_sellerAccountManager->getUnsoldArticles($unsoldArticlesParameter);

        parent::outputContent($result, 'Get Unsold Articles: ', true);

        $this->assertArrayHasKey('TotalLines', $result, 'Total Lines result is not defined');
        $this->assertArrayHasKey('UnsoldArticles', $result, 'Unsold Articles result is not returned');
    }

    public function testGetOpenArticles()
    {
        $openParameter = new OpenArticlesParameter();
        $openParameter
            ->setArticleTitleFilter(null)
            ->setArticleTypeFilter(ArticlesTypes::ALL)
            ->setInternalReferenceFilter(null)
            ->setNicknameFilter(null)
            ->setLastnameFilter(null)
            ->setPageSize(5);

        $result = $this->_sellerAccountManager->getOpenArticles($openParameter);

        parent::outputContent($result, 'Get Open Articles: ', true);

        $this->assertArrayHasKey('TotalLines', $result, 'Total Lines result is not defined');
        $this->assertArrayHasKey('OpenArticles', $result, 'Open Articles result is not returned');
    }

    public function testGetInTransitionArticles()
    {
        $inTransitionParameter = new GetInTransitionArticlesParameter();
        $inTransitionParameter
            ->setArticleTitleFilter(null)
            ->setLastname(null)
            ->setNickname(null)
            ->setInternalReferenceFilter(null)
            ->setPageSize(5)
            ->setTransitionStatusFilter(TransitionStatus::BOTH)
            ->setArticleTypeFilter(ArticleTypeFilter::ALL);

        $result = $this->_sellerAccountManager->getTransitionArticles($inTransitionParameter);

        parent::outputContent($result, 'Get Transition Articles: ', true);

        $this->assertArrayHasKey('TotalLines', $result, 'Total Lines result is not defined');
        $this->assertArrayHasKey('InTransitionArticles', $result, 'InTransitionArticles result is not returned');
    }

}