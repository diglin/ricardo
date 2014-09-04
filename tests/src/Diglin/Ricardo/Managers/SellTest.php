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
use Diglin\Ricardo\Enums\PictureExtension;
use Diglin\Ricardo\Enums\System\CategoryBrandingFilter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleDeliveryParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleDescriptionParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleInformationParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleInternalReferenceParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticlePictureParameter;
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

    public function testInsertArticle()
    {
        $insertArticleParameter = new InsertArticlesParameter();

        $system = new System($this->getServiceManager());
        $conditions = $system->getArticleConditions(false);
        $availabilities = $system->getAvailabilities();
        $categories = $system->getCategories(CategoryBrandingFilter::ONLYBRANDING);
        $warranties = $system->getWarranties();
        $paymentConditions = $system->getPaymentConditionsAndMethods();
        $deliveryConditions = $system->getDeliveryConditions();
        $partnerConfiguration = $system->getPartnerConfigurations();

        $delivery = new ArticleDeliveryParameter();
        $delivery
            // required
            ->setDeliveryCost(5)
            ->setIsDeliveryFree(false)
            ->setDeliveryId($deliveryConditions[0]['DeliveryConditionId'])
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
            ->setArticleDuration(5)
            ->setAvailabilityId($availabilities[0]['AvailabilityId'])
            ->setCategoryId($categories[10]['CategoryId'])
            ->setInitialQuantity(5)
            ->setIsCustomerTemplate(false)
            ->setIsRelistSoldOut(true)
            ->setMainPictureId(1) // @todo ask ricardo how to get the right value because the picture is not yet sent to the API so we do not have the index or may be it's a kind of "sort order" or "position" value
            ->setMaxRelistCount(5)
            ->setWarrantyId($warranties[1]['WarrantyId'])
            // optional
            ->setBuyNowPrice(20)
            ->setDeliveries($delivery)
            ->setIncrement(5)
            ->setInternalReferences($internalReferences)
            ->setPaymentConditionIds(array($paymentConditions[0]['PaymentConditionId']))
            ->setPaymentMethodIds(array($paymentConditions[0]['PaymentMethods'][0]['PaymentMethodId']))
            ->setStartDate(Helper::getJsonDate(time()))
            ->setStartPrice(5)
            ->setTemplateId(null);

        $descriptions = new ArticleDescriptionParameter();
        $descriptions
            // required
            ->setArticleTitle('My Product ' . $this->_generateRandomString(30))
            ->setArticleDescription($this->_generateRandomString(2000))
            ->setLanguageId($partnerConfiguration['LanguageId'])
            // optional
            ->setArticleSubtitle($this->_generateRandomString(60))
            ->setDeliveryDescription($this->_generateRandomString(2000))
            ->setPaymentDescription($this->_generateRandomString(2000))
            ->setWarrantyDescription($this->_generateRandomString(2000));


        $filename = '../../../media/pictures/22-syncmaster-lcd-monitor.jpg';
        $handle = fopen($filename, 'r');
        $imageContent = base64_encode(fread($handle, filesize($filename)));
        fclose($handle);

        $pictures = new ArticlePictureParameter();
        $pictures
            ->setPictureBytes($imageContent)
            ->setPictureExtension(PictureExtension::JPG)
            ->setPictureIndex(1);

        $antiforgeryToken = $this->getServiceManager()->getSecurityManager()->getAntiforgeryToken();

        $insertArticleParameter
            ->setAntiforgeryToken($antiforgeryToken)
            ->setArticleInformation($articleInformation)
            ->setDescriptions($descriptions)
            ->setIsUpdateArticle(false)
            ->setPictures($pictures);


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
