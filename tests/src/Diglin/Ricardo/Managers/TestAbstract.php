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

use \Diglin\Ricardo\Config;
use \Diglin\Ricardo\Api;
use \Diglin\Ricardo\Service;

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

abstract class TestAbstract extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Service
     */
    protected $_serviceManager;

    protected function tearDown()
    {
        $this->_serviceManager = null;
        parent::tearDown();
    }

    /**
     * Init the Service Manager to allow mapping between class managers and API services
     *
     * @return Service
     * @throws \Exception
     */
    protected function getServiceManager()
    {
        if (empty($this->_serviceManager)) {
            $configIniFile = __DIR__ . '/../../../../conf/config.ini';
            if (!file_exists($configIniFile)) {
                throw new \Exception('Missing Config.ini file');
            }

            $config = parse_ini_file(__DIR__ . '/../../../../conf/config.ini', true);

            if (isset($config['GERMAN'])) {
                $this->_serviceManager = new Service(new Api(new Config($config['GERMAN'])));
            } else {
                throw new \Exception('Missing GERMAN section in the ini file');
            }
        }

        return $this->_serviceManager;
    }

    /**
     * Get the last API Curl request for debug purpose
     *
     * @param bool $flush
     * @param bool $return
     * @param bool $log
     * @return mixed
     */
    protected function getLastApiDebug($flush = true, $return = true, $log = false)
    {
        $content = print_r($this->getServiceManager()->getApi()->getLastDebug($flush), true);

        if ($log) {
            $this->log($content);
        }

        if ($return) {
            return $content;
        }
    }

    /**
     * Get the content of some test variable
     *
     * @param array|int|string $output
     * @param string $testName
     * @param bool $debug
     */
    protected function outputContent($output, $testName = '', $debug = false)
    {
        if ($this->getServiceManager()->getConfig()->get('display_test_content')) {
            echo $testName . ' ' . print_r($output, true);
        }
        if ($debug) {
            echo $this->getLastApiDebug(false, false, true);
        }
        return;
    }

    protected function log($content)
    {
        $filename = __DIR__ . '/../../../../log/api.log';
        $handle = fopen($filename, 'a+');

        $time = date('Y-m-d H:i:s') . "\n";
        $content = $time . $content;

        fwrite($handle, $content);
        fclose($handle);

        return $this;
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
            ->setInternalReferenceValue('01234567890123456789');
//            ->setInternalReferenceValue('##PL##123##IT##456##SKU##MY_MAGENTO_SKU');

        $internalReferencesB = new ArticleInternalReferenceParameter();
        $internalReferencesB
            ->setInternalReferenceTypeId(InternalReferenceType::SELLERSPECIFIC)
            ->setInternalReferenceValue('98765432109876543210');

        $articleInformation = new ArticleInformationParameter();
        $articleInformation
            // required
            ->setArticleConditionId($conditions[0]['ArticleConditionId'])
            ->setArticleDuration(8 * 24 * 60) // 7 days
            ->setAvailabilityId($availabilities[0]['AvailabilityId'])
            ->setCategoryId(38828)
            ->setInitialQuantity(1)
            ->setIsCustomerTemplate(false)
            ->setMainPictureId(1)
            ->setMaxRelistCount(5)
            ->setWarrantyId($warranties[1]['WarrantyId'])
            ->setDeliveries($delivery)
            // optional
            ->setInternalReferences($internalReferences)
            ->setInternalReferences($internalReferencesB)
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

        if (!$auction && $buynow) {
            $articleInformation
                ->setIsRelistSoldOut(true);
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
}