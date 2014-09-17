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

use Diglin\Ricardo\Core\Helper;
use Diglin\Ricardo\Enums\Article\InternalReferenceType;
use Diglin\Ricardo\Enums\Article\PromotionCode;
use Diglin\Ricardo\Enums\PictureExtension;
use Diglin\Ricardo\Enums\System\CategoryBrandingFilter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleDeliveryParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleDescriptionParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleInformationParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleInternalReferenceParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticlePictureParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\CloseArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\DeletePlannedArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\DeletePlannedArticlesParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticleParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticlesParameter;

class SellTest extends TestAbstract
{
    /**
     * @var Sell
     */
    protected $_sellManager;

    protected function setUp()
    {
        $this->_sellManager = new Sell($this->getServiceManager());
        parent::setUp();
    }

    /**
     * @param int $length
     * @return string
     */
    protected function _generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * @param bool $auction
     * @param bool $buynow
     * @param bool $startNow
     * @return InsertArticleParameter
     */
    protected function getArticle($auction = true, $buynow = false, $startNow = false)
    {
        $insertArticleParameter = new InsertArticleParameter();

        $system = new System($this->getServiceManager());
        $conditions = $system->getArticleConditions(false);
        $availabilities = $system->getAvailabilities();
        //$categories = $system->getCategories(CategoryBrandingFilter::ONLYBRANDING);
        $warranties = $system->getWarranties();
        $paymentConditions = $system->getPaymentConditionsAndMethods();
        $deliveryConditions = $system->getDeliveryConditions();
        $partnerConfiguration = $system->getPartnerConfigurations();

        $delivery = new ArticleDeliveryParameter();
        $delivery
            // required
            ->setDeliveryCost(5)
            ->setIsDeliveryFree(0)
            ->setDeliveryId($deliveryConditions[0]['DeliveryConditionId'])
            ->setIsCumulativeShipping(0)
            // optional
            ->setDeliveryPackageSizeId($deliveryConditions[0]['PackageSizes'][0]['PackageSizeId']);

        $internalReferences = new ArticleInternalReferenceParameter();
        $internalReferences
            ->setInternalReferenceTypeId(InternalReferenceType::SELLERSPECIFIC)
            ->setInternalReferenceValue('MY_MAGENTO_SKU');

        $articleInformation = new ArticleInformationParameter();
        $articleInformation
            // required
            ->setArticleConditionId($conditions[0]['ArticleConditionId'])
            ->setArticleDuration(8 * 24 * 60) // 7 days
            ->setAvailabilityId($availabilities[0]['AvailabilityId'])
            ->setCategoryId(38828)
            ->setInitialQuantity(1)
            ->setIsCustomerTemplate(false)
            ->setIsRelistSoldOut(false) // @fixme seems to not be allowed why?
            ->setMainPictureId(1)
            ->setMaxRelistCount(5)
            ->setWarrantyId($warranties[1]['WarrantyId'])
            ->setDeliveries($delivery)
            // optional
            ->setInternalReferences($internalReferences)
            ->setPaymentConditionIds(array($paymentConditions[0]['PaymentConditionId']))
            ->setPaymentMethodIds(array($paymentConditions[0]['PaymentMethods'][0]['PaymentMethodId']))
            ->setTemplateId(null);

        if ($auction) {
            $articleInformation
                ->setIncrement(5)
                ->setStartPrice(5);
        }

        if ($auction && !$startNow) {
            $articleInformation
                ->setStartDate(Helper::getJsonDate(time() + 60*60));
        }

        if ($buynow) {
            $articleInformation->setBuyNowPrice(20);

            if ($auction) {
                $articleInformation
                    ->setPromotionIds(array(
                        PromotionCode::BUYNOW
                    ));
            }
        }

        $descriptions = new ArticleDescriptionParameter();
        $descriptions
            // required
            ->setArticleTitle('My Product ' . $this->_generateRandomString(20))
            ->setArticleDescription($this->_generateRandomString(2000))
            ->setLanguageId($partnerConfiguration['LanguageId'])
            // optional
            ->setArticleSubtitle($this->_generateRandomString(60))
            ->setDeliveryDescription($this->_generateRandomString(2000))
            ->setPaymentDescription($this->_generateRandomString(2000))
            ->setWarrantyDescription($this->_generateRandomString(2000));


        $filename = '../../../media/pictures/22-syncmaster-lcd-monitor.jpg';

        if (file_exists($filename)) {
            $imageContent = array_values(unpack('C*', file_get_contents($filename)));

        }

        $pictures = new ArticlePictureParameter();
        $pictures
            ->setPictureBytes($imageContent)
            ->setPictureExtension(Helper::getPictureExtension($filename))
            ->setPictureIndex(1);

        $antiforgeryToken = $this->getServiceManager()->getSecurityManager()->getAntiforgeryToken();
        $insertArticleParameter
            ->setAntiforgeryToken($antiforgeryToken)
            ->setArticleInformation($articleInformation)
            ->setDescriptions($descriptions)
            ->setPictures($pictures)
            ->setIsUpdateArticle(false);

        return $insertArticleParameter;
    }

    public function testInsertPlannedArticle()
    {
        $insertArticleParameter = $this->getArticle(true, true, false);

        try {
            $result = $this->_sellManager->insertArticle($insertArticleParameter);
        } catch (\Exception $e) {
            $this->getLastApiDebug(true, false, true);
            throw $e;
        }

        $this->assertArrayHasKey('PlannedArticleId', $result, 'Article does not have an article ID');
        $this->assertArrayHasKey('ArticleFee', $result, 'Article does not have any article fee');

        $this->outputContent($result, 'Insert Article: ', true);
    }

    /**
     * @depends testInsertPlannedArticle
     */
    public function testDeletePlannedArticle()
    {
        $insertArticleParameter = $this->getArticle(true, true, false);
        $result = $this->_sellManager->insertArticle($insertArticleParameter);

        $articleId = $result['PlannedArticleId'];

        $deleteParameter = new DeletePlannedArticleParameter();
        $deleteParameter
            ->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken())
            ->setPlannedArticleId($articleId);

        $result = $this->_sellManager->deletePlannedArticle($deleteParameter);

        $this->assertArrayHasKey('PlannedArticleId', $result, 'No PlannedArticleId found');
        $this->assertArrayHasKey('IsClosed', $result, 'No IsClosed found');
        $this->assertArrayHasKey('PlannedIndex', $result, 'No PlannedIndex found');
//        $this->assertEquals($articleId, $result['PlannedArticleId'], 'Article ID returned not equal'); //@fixme the Ricardo API doesn't provide any information if an article is deleted or not with this method

        $this->outputContent($result, 'Delete Planned Article: ');
    }

    public function testInsertPlannedArticles()
    {
        $insertArticles = new InsertArticlesParameter();

        $insertArticles->setArticles($this->getArticle());
        $insertArticles->setArticles($this->getArticle());

        try {
            $result = $this->_sellManager->insertArticles($insertArticles);
        } catch (\Exception $e) {
            $this->getLastApiDebug(true, false, true);
            throw $e;
        }

        $this->assertCount(2, $result, 'Two inserted article was expected.');
        $this->assertArrayHasKey('PlannedArticleId', $result[0], 'Article does not have an article ID');
        $this->assertArrayHasKey('ArticleFee', $result[0], 'Article does not have any article fee');

        $this->outputContent($result, 'Insert Articles: ');

        return array(
            $result[0]['PlannedArticleId'],
            $result[1]['PlannedArticleId']
        );
    }

    /**
     * @depends testInsertPlannedArticles
     */
    public function testDeletePlannedArticles($articles)
    {
        $deleteParameter = new DeletePlannedArticlesParameter();
        $deleteParameter
            ->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken());

        $article = new DeletePlannedArticleParameter();
        $article->setPlannedArticleId($articles[0]);
        $deleteParameter->setArticles($article);

        $article = new DeletePlannedArticleParameter();
        $article->setPlannedArticleId($articles[1]);
        $deleteParameter->setArticles($article);

        $result = $this->_sellManager->deletePlannedArticles($deleteParameter);

        $this->outputContent($result, 'Delete Planned Articles: ', true);

        $this->assertArrayHasKey('PlannedArticleId', $result[0], 'No PlannedArticleId found');
        $this->assertArrayHasKey('IsClosed', $result[0], 'No IsClosed found');
        $this->assertArrayHasKey('PlannedIndex', $result[0], 'No PlannedIndex found');
        $this->assertEquals($articles[0], $result[0]['PlannedArticleId'], 'Article ID returned not equal');

    }

    public function testCloseArticle()
    {
        $insertedArticle = $this->_sellManager->insertArticle($this->getArticle(true, true, true));

        $this->outputContent($insertedArticle, 'Inserted Buy Now Article with start now: ', true);

        $articleId = $insertedArticle['PlannedArticleId'];

        $closeParameter = new CloseArticleParameter();
        $closeParameter
            ->setAntiforgeryToken($this->_serviceManager->getSecurityManager()->getAntiforgeryToken())
            ->setArticleId($articleId);

        $result = $this->_sellManager->closeArticle($closeParameter);

        $this->outputContent($result, 'Close Article: ');

        $this->assertArrayHasKey('ArticleNr', $result, 'No article ID returned');
        $this->assertArrayHasKey('IsClosed', $result, 'Result does not have IsClosed Key');
        $this->assertTrue('IsClosed', (bool) $result['IsClosed'], 'Article not closed');
    }

    public function testRelistArticle()
    {
        $articleId = 729014362;

        $relist = $this->_sellManager->relistArticle($articleId);

        $this->outputContent($relist, 'Relist Article: ', true);
    }
}
