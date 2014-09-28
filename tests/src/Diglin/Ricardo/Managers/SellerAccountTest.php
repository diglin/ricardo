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

use Diglin\Ricardo\Core\Helper;
use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Enums\Article\CloseListStatus;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\ArticlesParameter;
use Diglin\Ricardo\Managers\SellerAccount\Parameter\ClosedArticlesParameter;
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
//        $closeParameter = new CloseArticleParameter();
//        $closeParameter
//            ->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken())
//            ->setArticleId($this->_articleId);
//
//        $this->_sellManager->closeArticle($closeParameter);

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

    public function totoGetArticle()
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

    public function totoGetArticles()
    {
        $articles = array();

        $sell = new Sell($this->getServiceManager());
        $result = $sell->insertArticle($this->getArticle(false, true, true));
        $articles[] = $result['ArticleId'];

        $result = $sell->insertArticle($this->getArticle(false, true, true));
        $articles[] = $result['ArticleId'];

        $articleParameter = new ArticlesParameter();
        $articleParameter
            ->setArticlesType(ArticlesTypes::ALL)
            ->setCloseStatus(CloseListStatus::OPEN);

        $result = $this->_sellerAccountManager->getArticles($articleParameter);

        parent::outputContent($result, 'Get Articles: ');

        $this->assertGreaterThanOrEqual(1, count($result), 'No result found');
        $this->assertArrayHasKey('ArticleId', $result[0], 'No article ID found');
        $this->assertArrayHasKey('ArticleInformationLimit', $result[0], 'Get Articles has no article information limit');

        $closeParameter = new CloseArticleParameter();
        $closeParameter
            ->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken())
            ->setArticleId($articles[0]);

        $sell->closeArticle($closeParameter);

        $closeParameter
            ->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken())
            ->setArticleId($articles[1]);

        $sell->closeArticle($closeParameter);
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
     * @todo difficult to test it, help to debug but lots of things must be dne manually
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
            ->setIsCompletedTransaction(false);

        $result = $this->_sellerAccountManager->getSoldArticles($articleParameter);

        parent::outputContent($result, 'Get Sold Articles: ');

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

}