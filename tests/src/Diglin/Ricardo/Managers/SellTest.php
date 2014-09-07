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
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticleParameter;

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

    public function testInsertArticle()
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
            ->setArticleDuration(7 * 24 * 60) // 7 days
            ->setAvailabilityId($availabilities[0]['AvailabilityId'])
            ->setCategoryId(38828)
            ->setInitialQuantity(1)
            ->setIsCustomerTemplate(false)
            ->setIsRelistSoldOut(false) // @fixme seems to not be allowed why?
            ->setMainPictureId(1)
            ->setMaxRelistCount(5)
            ->setWarrantyId($warranties[1]['WarrantyId'])
            ->setPromotionIds(array(
                PromotionCode::BUYNOW
            ))
            ->setDeliveries($delivery)
            // optional
            ->setBuyNowPrice(20)
            ->setIncrement(5)
            ->setInternalReferences($internalReferences)
            ->setPaymentConditionIds(array($paymentConditions[0]['PaymentConditionId']))
            ->setPaymentMethodIds(array($paymentConditions[0]['PaymentMethods'][0]['PaymentMethodId']))
            ->setStartDate(Helper::getJsonDate(time() + 60*60))
            ->setStartPrice(5)
            ->setTemplateId(null);

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




        try {
        $result = $this->_sellManager->insertArticle($insertArticleParameter);
        } catch (\Exception $e) {
//            print_r($e);
            $this->getLastApiDebug(true, false, true);
            throw $e;
            return;
        }

        $this->outputContent($result, 'Insert Article: ', true);
    }

    protected function _generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
