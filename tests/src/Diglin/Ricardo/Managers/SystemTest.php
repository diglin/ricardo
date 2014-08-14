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

use Diglin\Ricardo\Enums\PromotionCode;

class SystemTest extends TestAbstract
{
    /**
     * @var System
     */
    protected $_systemManager;

    protected function setUp()
    {
        $this->_systemManager = new System($this->getServiceManager());
        parent::setUp();
    }

    public function testGetAllErrorsCodes()
    {
        $result = $this->_systemManager->getAllErrorsCodes();
        $error = array_pop($result);
        $this->assertArrayHasKey('ErrorId', $error[0], 'GetAllErrorsCodes: no error ID found');
        $this->assertArrayHasKey('ErrorText', $error[0], 'GetAllErrorsCodes: no error text found');
    }

    public function testGetArticleConditions()
    {
        $result = $this->_systemManager->getArticleConditions(false);

        $this->assertGreaterThanOrEqual(1, count($result), 'Article Conditions are empty');
        $this->assertArrayHasKey('ArticleConditionText', $result[0], 'Data returned for the article conditions is not correct');
    }

    public function testGetAvailabilities()
    {
        $result = $this->_systemManager->getAvailabilities();

        $this->assertGreaterThanOrEqual(1, count($result), 'Article availabilities are empty');
        $this->assertArrayHasKey('AvailabilityText', $result[0], 'Data returned for the article availabilities is not correct');

        self::outputContent($result, 'Shipping Availabilities: ');
    }

    public function testGetCategories()
    {
        // Set category_branding_filter = 0, you will get all categories, be aware it raises the memory to 24MB
        $result = $this->_systemManager->getCategories(2, true); //@todo replace 2 by an enum if exist on ricardo api side

        $numberOfCategories = count($result);

        $this->assertGreaterThanOrEqual(1, $numberOfCategories, 'No category found');
        $this->assertArrayHasKey('CategoryId', $result[0], 'Category data structure is wrong');
        $this->assertLessThanOrEqual(800, $numberOfCategories, 'Number of categories too high comparaed to the one expected - 800'); // category_branding_filter set to 2, decrease the number of categories

        return $result[10]['CategoryId'];
    }

    /**
     * @depends testGetCategories
     */
    public function testGetCategory($categoryId)
    {
        $result = $this->_systemManager->getCategory($categoryId);

        $this->assertArrayHasKey('CategoryId', $result, 'Category data structure does not have CategoryId');
        $this->assertArrayHasKey('CategoryName', $result, 'Category data structure does not have CategoryName');
    }

    public function testGetCountries()
    {
        $result = $this->_systemManager->getCountries();

        $this->assertGreaterThanOrEqual(10, count($result), 'No country found');
        $this->assertArrayHasKey('CountryId', $result[0], 'Country data structure is wrong');
    }

    public function testGetDeliveryConditions()
    {
        $result = $this->_systemManager->getDeliveryConditions();

        $this->assertGreaterThanOrEqual(10, count($result), 'No delivery condition found');
        $this->assertArrayHasKey('DeliveryConditionId', $result[0], 'Delivery condition data structure is wrong');
        $this->assertArrayHasKey('PackageSizes', $result[0], 'PackageSizes is missing');
        $this->assertArrayHasKey('PackageSizeCost', $result[0]['PackageSizes'][0], 'Delivery Package Size data structure is wrong');

        self::outputContent($result, 'Delivery Conditions: ');
    }

    /**
     * @depends testGetCategories
     */
    public function testGetFirstChildsCategories($categoryId)
    {
        // Set category_branding_filter = 0, you will get all categories, be aware it raises the memory to 24MB
        $result = $this->_systemManager->getFirstChildsCategories($categoryId, 2, true);

        $this->assertGreaterThanOrEqual(1, count($result), 'No category childs found');
        $this->assertArrayHasKey('CategoryId', $result[0], 'Category childs data structure does not have CategoryId');
        $this->assertArrayHasKey('CategoryName', $result[0], 'Category childs data structure does not have CategoryName');
    }

    public function testGetLanguages()
    {
        $result = $this->_systemManager->getLanguages();

        $this->assertGreaterThanOrEqual(1, count($result), 'No languages found');
        $this->assertArrayHasKey('LanguageId', $result[0], 'Languages data structure does not have LanguageId');
        $this->assertArrayHasKey('IsMainLanguage', $result[0], 'Languages data structure does not have IsMainLanguage');
        $this->assertArrayHasKey('LanguageText', $result[0], 'Languages data structure does not have LanguageText');
    }

    public function testGetPackages()
    {
        $result = $this->_systemManager->getPackages();

        $this->assertGreaterThanOrEqual(1, count($result), 'No Package found');
        $this->assertArrayHasKey('PackageId', $result[0], 'Packages data structure does not have PackageId');
        $this->assertArrayHasKey('PackagePrice', $result[0], 'Packages data structure does not have PackagePrice');
        $this->assertArrayHasKey('PackageSize', $result[0], 'Packages data structure does not have PackageSize');
    }

    /**
     * @depends testGetCategories
     */
    public function testGetParentsCategories($categoryId)
    {
        $result = $this->_systemManager->getParentsCategories($categoryId);

        $this->assertGreaterThanOrEqual(1, count($result), 'No category parents found');
        $this->assertArrayHasKey('CategoryId', $result[0], 'Category parents data structure does not have CategoryId');
        $this->assertArrayHasKey('CategoryName', $result[0], 'Category parents data structure does not have CategoryName');
    }

    public function testGetPartnerConfigurations()
    {
        $result = $this->_systemManager->getPartnerConfigurations();

        $this->assertGreaterThanOrEqual(5, count($result), 'No configuration found');
        $this->assertArrayHasKey('CurrencyId', $result, 'Configuration CurrencyId missing');
        $this->assertArrayHasKey('DataProtectionUrl', $result, 'Configuration DataProtectionUrl missing');
        $this->assertArrayHasKey('DomainName', $result, 'Configuration DomainName missing');
        $this->assertArrayHasKey('MaxSellingDuration', $result, 'Configuration MaxSellingDuration missing');

        self::outputContent($result, 'Partner Configuration: ');
    }

    public function testGetPaymentConditions()
    {
        $result = $this->_systemManager->getPaymentConditions();

        $this->assertGreaterThanOrEqual(1, count($result), 'No Payment Condition found');
        $this->assertArrayHasKey('PaymentConditionId', $result[0], 'Payment Condition PaymentConditionId missing');
        $this->assertArrayHasKey('PaymentConditionText', $result[0], 'Payment Condition PaymentConditionText missing');
        $this->assertArrayHasKey('PaymentMethods', $result[0], 'Payment Condition PaymentMethods missing');

        self::outputContent($result, 'Payment Conditions: ');

        return $result[0]['PaymentConditionId'];
    }

    /**
     * @depends testGetPaymentConditions
     */
    public function testGetPaymentConditionsAndMethods()
    {
        $result = $this->_systemManager->getPaymentConditionsAndMethods();

        $this->assertArrayHasKey('PaymentConditionId', $result[0], 'Payment Condition & Methods PaymentConditionId missing');
        $this->assertArrayHasKey('PaymentConditionText', $result[0], 'Payment Condition & Methods PaymentConditionText missing');
        $this->assertArrayHasKey('PaymentMethods', $result[0], 'Payment Condition & Methods PaymentMethods missing');

        self::outputContent($result, 'Payment Conditions & Methods: ');

        return $result[0]['PaymentConditionId'];
    }

    /**
     * @depends testGetPaymentConditionsAndMethods
     */
    public function testGetPaymentMethods($paymentConditionId)
    {
        $result = $this->_systemManager->getPaymentMethods(true, $paymentConditionId); // $paymentConditionId has no effect on the Ricardo API side ! @fixme

        //$this->assertCount(1, $result[0], 'More than 1 Payment Methods found instead to get only the one provided in parameter ' . $paymentConditionId);
        $this->assertArrayHasKey('PaymentMethodId', $result[0], 'Payment Methods PaymentMethodId missing');
        $this->assertArrayHasKey('PaymentMethodText', $result[0], 'Payment Methods PaymentMethodText missing');

        $this->_systemManager->setPaymentMethods(null);
        $result = $this->_systemManager->getPaymentMethods(false);

        $this->assertGreaterThanOrEqual(5, count($result), 'Payment Methods does not get the whole list of methods, even those which are not allow to sell');

        self::outputContent($result, 'Payment Methods: ');
    }

    public function testGetPhonePrefixes()
    {
        $result = $this->_systemManager->getPhonePrefixes();

        $this->assertGreaterThanOrEqual(10, count($result), 'No phone prefixes found');
        $this->assertArrayHasKey('Value', $result[0], 'Key "Value" not found for phone prefixes');
        $this->assertContains('+', $result[0]['Value'], 'No phone prefix value found');
    }

    /**
     * @depends testGetCategories
     */
    public function testGetPromotions($categoryId)
    {
        $result = $this->_systemManager->getPromotions(
            \Diglin\Ricardo\Core\Helper::getJsonDate(), \Diglin\Ricardo\Enums\CategoryArticleType::ALL, $categoryId, 1
        );

        $this->assertArrayHasKey('GroupId', $result[0], 'Promotions: GroupId is missing');
        $this->assertArrayHasKey('IsMandatory', $result[0], 'Promotions: IsMandatory is missing');
        $this->assertArrayHasKey('PromotionFee', $result[0], 'Promotions: PromotionFee is missing');
        $this->assertArrayHasKey('PromotionId', $result[0], 'Promotions: PromotionId is missing');
        $this->assertArrayHasKey('PromotionLabel', $result[0], 'Promotions: PromotionLabel is missing');

        self::outputContent($result, 'Get Promotions: ');
    }

    /**
     * @depends testGetCountries
     */
    public function testGetRegions()
    {
        $countries = $this->_systemManager->getCountries();
        $result = $this->_systemManager->getRegions($countries[0]['CountryId']);
        $this->assertArrayHasKey('RegionId', $result[0], 'Regions: RegionId is missing');
        $this->assertArrayHasKey('RegionName', $result[0], 'Regions: RegionName is missing');
    }

    public function testGetTemplates()
    {
        $result = $this->_systemManager->getTemplates();

        if (count($result) == 0) {
            echo 'No template found, Test testGetTemplates skipped' . "\n";
        } else {
            $this->assertArrayHasKey('TemplateId', $result[0], 'Templates: TemplateId is missing');
        }
    }

    public function testGetWarranties()
    {
        $result = $this->_systemManager->getWarranties();
        $this->assertArrayHasKey('WarrantyConditionText', $result[0], 'Warranties: WarrantyConditionText is missing');
        $this->assertArrayHasKey('WarrantyId', $result[0], 'Warranties: WarrantyId is missing');
    }
}