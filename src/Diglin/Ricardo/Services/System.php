<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Services;

/**
 * Class System
 * This service exposes referential data about the ricardo's system, for example available languages, list of categories, list of possible packages
 *
 * @package Diglin\Ricardo\Services
 */
class System extends ServiceAbstract
{
    /**
     * @var string
     */
    protected $_service = 'SystemService';

    /**
     * @var string
     */
    protected $_typeOfToken = self::TOKEN_TYPE_ANONYMOUS;

    /**
     * Gets all errors code list.
     *
     * @return array
     */
    public function getAllErrorsCodes()
    {
        return array(
            'method' => 'GetAllErrorsCodes',
            'params' => array('getAllErrorsCodesParameter' => array())
        );
    }

    /**
     * Gets if on the current country the user is allowed to activate is account.
     *
     * @return array
     */
    public function getAllowedToActivateAccount()
    {
        return array(
            'method' => 'GetAllowedToActivateAccount',
            'params' => array('getAllowedToActivateAccountParameter' => array())
        );
    }

    /**
     * Gets the article conditions.
     *
     * @return array
     */
    public function getArticleConditions()
    {
        return array(
            'method' => 'GetArticleConditions',
            'params' => array('getArticleConditionsParameter' => array())
        );
    }

    /**
     * Gets the availabilities.
     *
     * @return array
     */
    public function getAvailabilities()
    {
        return array(
            'method' => 'GetAvailabilities',
            'params' => array('getAvailabilitiesParameter' => array())
        );
    }

    /**
     * Gets the categories.
     *
     * @return array
     */
    public function getCategories()
    {
        return array(
            'method' => 'GetCategories',
            'params' => array('getCategoriesParameter' => array())
        );
    }

    /**
     * Gets the category.
     *
     * @return array
     */
    public function getCategory()
    {
        return array(
            'method' => 'GetCategory',
            'params' => array('getCategoryParameter' => array())
        );
    }

    /**
     * Gets the countries.
     *
     * @return array
     */
    public function getCountries()
    {
        return array(
            'method' => 'GetCountries',
            'params' => array('getCountriesParameter' => array())
        );
    }

    /**
     * Gets the 2 letter country ISO.
     *
     * @return array
     */
    public function getCountryIso()
    {
        return array(
            'method' => 'GetCountryIso',
            'params' => array('getCountryIsoParameter' => array())
        );
    }

    /**
     * Gets the delivery conditions.
     *
     * @return array
     */
    public function getDeliveryConditions()
    {
        return array(
            'method' => 'GetDeliveryConditions',
            'params' => array('getDeliveryConditionsParameter' => array())
        );
    }

    /**
     * Gets the first childs categories.
     *
     * @return array
     */
    public function getFirstChildsCategories()
    {
        return array(
            'method' => 'GetFirstChildsCategories',
            'params' => array('getFirstChildsCategoriesParameter' => array())
        );
    }

    /**
     * Gets available headers and footers
     *
     * @return array
     */
    public function getHeaderAndFooterTexts()
    {
        return array(
            'method' => 'GetHeaderAndFooterTexts',
            'params' => array('getHeaderAndFooterTextsParameter' => array())
        );
    }

    /**
     * Gets the increment.
     *
     * @return array
     */
    public function getIncrements()
    {
        return array(
            'method' => 'GetIncrements',
            'params' => array('getIncrementsParameter' => array())
        );
    }

    /**
     * Gets the languages.
     *
     * @return array
     */
    public function getLanguages()
    {
        return array(
            'method' => 'GetLanguages',
            'params' => array('getLanguagesParameter' => array())
        );
    }

    /**
     * Gets list of listing packages
     *
     * @return array
     */
    public function getPackages()
    {
        return array(
            'method' => 'GetArticleConditions',
            'params' => array('getPackagesParameter' => array())
        );
    }

    /**
     * Gets the parents categories.
     *
     * @return array
     */
    public function getParentsCategories()
    {
        return array(
            'method' => 'GetParentsCategories',
            'params' => array('getParentsCategoriesParameter' => array())
        );
    }

    /**
     * Gets the partner configurations.
     *
     * @return array
     */
    public function getPartnerConfigurations()
    {
        return array(
            'method' => 'GetPartnerConfigurations',
            'params' => array('getPartnerConfigurationsParameter' => array())
        );
    }

    /**
     * Gets the payment conditions.
     *
     * @return array
     */
    public function getPaymentConditions()
    {
        return array(
            'method' => 'GetPaymentConditions',
            'params' => array('getPaymentConditionsParameter' => array())
        );
    }

    /**
     * Gets the payment conditions and payment function associated.
     *
     * @return array
     */
    public function getPaymentConditionsAndMethods()
    {
        return array(
            'method' => 'GetPaymentConditionsAndMethods',
            'params' => array('getPaymentConditionsParameter' => array())
        );
    }

    /**
     * Gets the payment functions.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        return array(
            'method' => 'GetPaymentMethods',
            'params' => array('getPaymentMethodsParameter' => array())
        );
    }

    /**
     * Gets the phone prefixes.
     *
     * @return array
     */
    public function getPhonePrefixes()
    {
        return array(
            'method' => 'GetPhonePrefixes',
            'params' => array('getPhonePrefixesParameter' => array())
        );
    }

    /**
     * Gets the promotions.
     *
     * @return array
     */
    public function getPromotions()
    {
        return array(
            'method' => 'GetPromotions',
            'params' => array('getPromotionsParameter' => array())
        );
    }

    /**
     * Gets the regions.
     *
     * @return array
     */
    public function getRegions()
    {
        return array(
            'method' => 'GetRegions',
            'params' => array('getRegionsParameter' => array())
        );
    }

    /**
     * Gets the template.
     *
     * @return array
     */
    public function getTemplates()
    {
        return array(
            'method' => 'GetTemplates',
            'params' => array('getTemplatesParameter' => array())
        );
    }

    /**
     * Gets the warranties.
     *
     * @return array
     */
    public function getWarranties()
    {
        return array(
            'method' => 'GetWarranties',
            'params' => array('getWarrantiesParameter' => array())
        );
    }

    /**
     * Inserts the customer workstation mapping.
     *
     * @return array
     */
    public function insertCustomerWorkstation()
    {
        return array(
            'method' => 'InsertCustomerWorkstation',
            'params' => array('insertCustomerWorkstationParameter' => array('CustomerId' => '', 'WorkstationId' => ''))
        );
    }

    /**
     * Inserts the device log.
     *
     * @return array
     */
    public function insertDeviceLog()
    {
        return array(
            'method' => 'InsertDeviceLog',
            'params' => array('insertDeviceLogParameter' => array())
        );
    }

    /**
     * Inserts Optimizely traffic logs
     *
     * @return array
     */
    public function insertOptimizelyTrafficLog()
    {
        return array(
            'method' => 'InsertOptimizelyTrafficLog',
            'params' => array('insertOptimizelyTrafficLogParameter' => array())
        );
    }

    /**
     * Inserts the traffic log.
     *
     * @return array
     */
    public function insertTrafficLog()
    {
        return array(
            'method' => 'InsertTrafficLog',
            'params' => array('insertTrafficLogParameter' => array())
        );
    }

    public function webAlertCheck()
    {
        return array(
            'method' => 'WebAlertCheck',
            'params' => array()
        );
    }
}