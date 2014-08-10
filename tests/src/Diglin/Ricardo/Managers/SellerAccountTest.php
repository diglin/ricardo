<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    magento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo\Managers;

use Diglin\Ricardo\Enums\ArticlesTypes;
use Diglin\Ricardo\Enums\CloseStatus;

class SellerAccountTest extends TestAbstract
{
    /**
     * @var SellerAccount
     */
    protected $_sellerAccountManager;

    protected function setUp()
    {
        $this->_sellerAccountManager = new SellerAccount($this->getServiceManager());

        //@todo insert article in Ricardo
        //@todo add a template

        parent::setUp();
    }

    protected function tearDown()
    {
        //@todo remove the article
        //@todo remove the template

        parent::tearDown();
    }

    public function testAddCardPaymentOption()
    {
        //@todo provide an article id or more to do the test

        $result = $this->_sellerAccountManager->addCardPaymentOption(array());
    }

    public function testGetArticleModificationAllowed()
    {
        //@todo provide an article id to do the test

        $result = $this->_sellerAccountManager->getArticleModificationAllowed(0);
    }

    public function testGetArticle()
    {
        //@todo create an article id to do the test

        $articleId = 728981868;

        $result = $this->_sellerAccountManager->getArticle($articleId);

        $this->assertArrayHasKey('ArticleInformation', $result, 'The article ' . $articleId . ' has no article information');
        $this->assertArrayHasKey('BidInformation', $result, 'The article ' . $articleId . ' has bid information');
        $this->assertArrayHasKey('Deliveries', $result, 'The article ' . $articleId . ' has no delivery');
        $this->assertArrayHasKey('Descriptions', $result, 'The article ' . $articleId . ' has no description');
        $this->assertArrayHasKey('Pictures', $result, 'The article ' . $articleId . ' has no picture');
    }

    public function testGetArticles()
    {
        //@todo create at least one or more article ids to do the test

        $result = $this->_sellerAccountManager->getArticles(ArticlesTypes::ALL, CloseStatus::OPEN);

        $this->assertGreaterThanOrEqual(0, count($result), 'No result found');
        $this->assertArrayHasKey('ArticleId', $result[0], 'No article ID found');
        $this->assertArrayHasKey('ArticleInformationLimit', $result[0], 'Get Articles has no article information limit');
    }

    public function testGetTemplates()
    {
        $result = $this->_sellerAccountManager->getTemplates();
        $this->assertGreaterThanOrEqual(1, count($result), 'Number of templates found is not greater than 1. Create one on ricardo.ch or the sandbox, not into the ricardo Assistant');
        print_r($result);
    }

    public function testGetSellerPackages()
    {
        $result = $this->_sellerAccountManager->getSellerPackages();
//        echo $this->getLastApiDebug();
//        print_r($result);
    }
}