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
     * Gets the article conditions list
     *
     * @param bool $isGroup
     * @return array
     */
    public function getArticleConditions($isGroup)
    {
        return array(
            'method' => 'GetArticleConditions',
            'params' => array('getArticleConditionsParameter' => array('IsGroup' => (bool) $isGroup))
        );
    }

    /**
     * Gets the result article conditions list
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetArticleConditionsResult": {
     *       "ArticleConditions": [{
     *         "ArticleConditionId": "ID",
     *         "ArticleConditionText": "TEXT",
     *         "IsGroup": "TEXT"
     *       },
     *       {
     *         "ArticleConditionId": "ID",
     *         "ArticleConditionText": "TEXT",
     *         "IsGroup": "TEXT"
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getArticleConditionsResult(array $data)
    {
        if (isset($data['GetArticleConditionsResult']) && isset($data['GetArticleConditionsResult']['ArticleConditions'])) {
            return $data['GetArticleConditionsResult']['ArticleConditions'];
        }
        return array();
    }

    /**
     * Get the product availabilities.
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
     * Get the product availabilities.
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetAvailabilitiesResult": {
     *       "Availabilities": [{
     *         "AvailabilityId": "ID",
     *         "AvailabilityText": "TEXT"
     *       },
     *       {
     *         "AvailabilityId": "ID",
     *         "AvailabilityText": "TEXT"
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getAvailabilitiesResult(array $data)
    {
        if (isset($data['GetAvailabilitiesResult']) && isset($data['GetAvailabilitiesResult']['Availabilities'])) {
            return $data['GetAvailabilitiesResult']['Availabilities'];
        }

        return array();
    }

    /**
     * Gets the categories list
     * AllCategories:
     * - 0 = Doesn't filter any categories
     * - 1 = Filter the categories without branding ones
     * - 2 = Filter categories to get only the branding ones
     *
     * @return array
     */
    public function getCategories()
    {
        $categoryBrandingFilter = 0;
        $onlyAllowToSell = true;

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['category_branding_filter'])) ? $categoryBrandingFilter = $args[0]['category_branding_filter'] : '';
            (isset($args[0]['only_allow_to_sell'])) ? $onlyAllowToSell = $args[0]['only_allow_to_sell'] : '';
        }

        return array(
            'method' => 'GetCategories',
            'params' => array('getCategoriesParameter' => array('CategoryBrandingFilter' => (int) $categoryBrandingFilter, 'OnlyAllowToSell' => (int) $onlyAllowToSell))
        );
    }

    /**
     * Gets the categories list result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetCategoriesResult": {
     *       "Categories": [{
     *         "ArticleTypeId": "INT",
     *         "CategoryId": "INT",
     *         "CategoryName": "TEXT",
     *         "CategoryNameRewritten": "TEXT",
     *         "CategoryTypeId": "INT",
     *         "IsBranding": "INT",
     *         "IsFinal": "INT",
     *         "Level": "INT",
     *         "ParentId": "INT",
     *         "PartialUrl": "TEXT"
     *       },
     *       {
     *         "ArticleTypeId": "INT",
     *         "CategoryId": "INT",
     *         "CategoryName": "TEXT",
     *         "CategoryNameRewritten": "TEXT",
     *         "CategoryTypeId": "INT",
     *         "IsBranding": "INT",
     *         "IsFinal": "INT",
     *         "Level": "INT",
     *         "ParentId": "INT",
     *         "PartialUrl": "TEXT"
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getCategoriesResult(array $data)
    {
        if (isset($data['GetCategoriesResult']) && isset($data['GetCategoriesResult']['Categories'])) {
            return $data['GetCategoriesResult']['Categories'];
        }

        return array();
    }

    /**
     * Get a category
     *
     * @param int $categoryId
     * @return array
     */
    public function getCategory($categoryId)
    {
        return array(
            'method' => 'GetCategory',
            'params' => array('getCategoryParameter' => array('CategoryId' => (int) $categoryId))
        );
    }

    /**
     * Get a category result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetCategoriesResult": {
     *       "Category": [{
     *         "ArticleTypeId": "INT",
     *         "CategoryId": "INT",
     *         "CategoryName": "TEXT",
     *         "CategoryNameRewritten": "TEXT",
     *         "CategoryTypeId": "INT",
     *         "IsBranding": "INT",
     *         "IsFinal": "INT",
     *         "Level": "INT",
     *         "ParentId": "INT",
     *         "PartialUrl": "TEXT"
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getCategoryResult(array $data)
    {
        if (isset($data['GetCategoryResult']) && isset($data['GetCategoryResult']['Category'])) {
            return $data['GetCategoryResult']['Category'];
        }

        return array();
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
     * Get the countries result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetCountriesResult": {
     *       "Countries": [{
     *         "CountryId": "INT",
     *         "CountryName": "TEXT",
     *        },
     *        {
     *         "CountryId": "INT",
     *         "CountryName": "TEXT",
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getCountriesResult(array $data)
    {
        if (isset($data['GetCountriesResult']) && isset($data['GetCountriesResult']['Countries'])) {
            return $data['GetCountriesResult']['Countries'];
        }

        return array();
    }

    /**
     * Gets the 2 letter country ISO.
     *
     * @FIXME seems to not work on Ricardo side
     *
     * @param string $ip
     * @return array
     */
//    public function getCountryIso($ip)
//    {
//        return array(
//            'method' => 'GetCountryIso',
//            'params' => array('getCountryIsoParameter' => array('IpAddress' => $ip))
//        );
//    }

    /**
     * Gets the 2 letter country ISO result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetCountryIsoResult": {
     *       "CountryIso": [{
     *         "CountryId": "INT",
     *         "CountryName": "TEXT",
     *        },
     *        {
     *         "CountryId": "INT",
     *         "CountryName": "TEXT",
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
//    public function getCountryIsoResult(array $data)
//    {
//        if (isset($data['GetCountryIsoResult']) && isset($data['GetCountryIsoResult']['CountryIso'])) {
//            return $data['GetCountryIsoResult']['CountryIso'];
//        }
//
//        return array();
//    }

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
     * Get the delivery conditions result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetDeliveryConditionsResult": {
     *       "DeliveryConditions": [{
     *         "DeliveryConditionId": "INT",
     *         "DeliveryConditionText": "TEXT",
     *         "DeliveryCost": "INT"
     *         "PackageSizes": [
     *             {
     *                 "PackageSizeCost": "INT"
     *                 "PackageSizeId": "INT"
     *                 "PackageSizeText": "TEXT"
     *             },
     *             {
     *                 "PackageSizeCost": "INT"
     *                 "PackageSizeId": "INT"
     *                 "PackageSizeText": "TEXT"
     *             }
     *         ]},
     *        },
     *         "DeliveryConditionId": "INT",
     *         "DeliveryConditionText": "TEXT",
     *         "DeliveryCost": "INT"
     *         "PackageSizes": [
     *             {
     *                 "PackageSizeCost": "INT"
     *                 "PackageSizeId": "INT"
     *                 "PackageSizeText": "TEXT"
     *             },
     *             {
     *                 "PackageSizeCost": "INT"
     *                 "PackageSizeId": "INT"
     *                 "PackageSizeText": "TEXT"
     *             }
     *         ]}
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getDeliveryConditionsResult(array $data)
    {
        if (isset($data['GetDeliveryConditionsResult']) && isset($data['GetDeliveryConditionsResult']['DeliveryConditions'])) {
            return $data['GetDeliveryConditionsResult']['DeliveryConditions'];
        }

        return array();
    }

    /**
     * Gets the first childs categories.
     *
     * @return array
     */
    public function getFirstChildsCategories()
    {
        $categoryBrandingFilter = 0;
        $onlyAllowToSell = true;
        $categoryId = 0;

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['category_branding_filter'])) ? $categoryBrandingFilter = $args[0]['category_branding_filter'] : '';
            (isset($args[0]['only_allow_to_sell'])) ? $onlyAllowToSell = $args[0]['only_allow_to_sell'] : '';
            (isset($args[0]['category_id'])) ? $categoryId = $args[0]['category_id'] : '';
        }

        return array(
            'method' => 'GetFirstChildsCategories',
            'params' => array('getFirstChildsCategoriesParameter' =>
                array(
                'CategoryBrandingFilter' => $categoryBrandingFilter,
                'CategoryId' => (int) $categoryId,
                'OnlyAllowToSell' => (int) $onlyAllowToSell))
        );
    }

    /**
     * Gets the first childs categories result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetFirstChildsCategoriesResult": {
     *       "Categories": [{
     *         "ArticleTypeId": "INT",
     *         "CategoryId": "INT",
     *         "CategoryName": "TEXT",
     *         "CategoryNameRewritten": "TEXT",
     *         "CategoryTypeId": "INT",
     *         "IsBranding": "INT",
     *         "IsFinal": "INT",
     *         "Level": "INT",
     *         "ParentId": "INT",
     *         "PartialUrl": "TEXT"
     *       },
     *       {
     *         "ArticleTypeId": "INT",
     *         "CategoryId": "INT",
     *         "CategoryName": "TEXT",
     *         "CategoryNameRewritten": "TEXT",
     *         "CategoryTypeId": "INT",
     *         "IsBranding": "INT",
     *         "IsFinal": "INT",
     *         "Level": "INT",
     *         "ParentId": "INT",
     *         "PartialUrl": "TEXT"
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getFirstChildsCategoriesResult(array $data)
    {
        if (isset($data['GetFirstChildsCategoriesResult']) && isset($data['GetFirstChildsCategoriesResult']['Categories'])) {
            return $data['GetFirstChildsCategoriesResult']['Categories'];
        }

        return array();
    }

    /**
     * Gets available headers and footers
     *
     * @return array
     */
//    public function getHeaderAndFooterTexts()
//    {
//        return array(
//            'method' => 'GetHeaderAndFooterTexts',
//            'params' => array('getHeaderAndFooterTextsParameter' => array())
//        );
//    }
//
//    public function getHeaderAndFooterTextsResult(array $data)
//    {
//        if (isset($data['GetHeaderAndFooterTextsResult']) && isset($data['GetHeaderAndFooterTextsResult']['HeadersAndFooters'])) {
//            return $data['GetHeaderAndFooterTextsResult']['HeadersAndFooters'];
//        }
//
//        return array();
//    }

    /**
     * Gets the increment.
     *
     * @return array
     */
//    public function getIncrements()
//    {
//        return array(
//            'method' => 'GetIncrements',
//            'params' => array('getIncrementsParameter' => array())
//        );
//    }

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
     * Get the languages result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetLanguagesResult": {
     *       "Languages": [{
     *         "IsMainLanguage": "INT",
     *         "LanguageId": "INT",
     *         "LanguageText": "TEXT",
     *        },
     *        {
     *         "IsMainLanguage": "INT",
     *         "LanguageId": "INT",
     *         "LanguageText": "TEXT",
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getLanguagesResult(array $data)
    {
        if (isset($data['GetLanguagesResult']) && isset($data['GetLanguagesResult']['Languages'])) {
            return $data['GetLanguagesResult']['Languages'];
        }

        return array();
    }

    /**
     * Gets list of listing packages
     *
     * @param int $packageType
     * @return array
     */
    public function getPackages($packageType = 0)
    {
        return array(
            'method' => 'GetPackages',
            'params' => array('getPackagesParameter' => array('PackageType' => (int) $packageType))
        );
    }

    /**
     * Get the languages result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetPackagesResult": {
     *       "Packages": [{
     *         "PackageId": "INT",
     *         "PackagePrice": "INT",
     *         "PackageSize": "INT",
     *        },
     *        {
     *         "PackageId": "INT",
     *         "PackagePrice": "INT",
     *         "PackageSize": "INT",
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getPackagesResult(array $data)
    {
        if (isset($data['GetPackagesResult']) && isset($data['GetPackagesResult']['Packages'])) {
            return $data['GetPackagesResult']['Packages'];
        }

        return array();
    }

    /**
     * Gets the parents categories.
     *
     * @param int $categoryId
     * @return array
     */
    public function getParentsCategories($categoryId)
    {
        return array(
            'method' => 'GetParentsCategories',
            'params' => array('getParentsCategoriesParameter' => array('CategoryId' => (int) $categoryId))
        );
    }

    /**
     * Gets the parents categories result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetParentsCategoriesResult": {
     *       "Categories": [{
     *         "ArticleTypeId": "INT",
     *         "CategoryId": "INT",
     *         "CategoryName": "TEXT",
     *         "CategoryNameRewritten": "TEXT",
     *         "CategoryTypeId": "INT",
     *         "IsBranding": "INT",
     *         "IsFinal": "INT",
     *         "Level": "INT",
     *         "ParentId": "INT",
     *         "PartialUrl": "TEXT"
     *       },
     *       {
     *         "ArticleTypeId": "INT",
     *         "CategoryId": "INT",
     *         "CategoryName": "TEXT",
     *         "CategoryNameRewritten": "TEXT",
     *         "CategoryTypeId": "INT",
     *         "IsBranding": "INT",
     *         "IsFinal": "INT",
     *         "Level": "INT",
     *         "ParentId": "INT",
     *         "PartialUrl": "TEXT"
     *       }]
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getParentsCategoriesResult(array $data)
    {
        if (isset($data['GetParentsCategoriesResult']) && isset($data['GetParentsCategoriesResult']['Categories'])) {
            return $data['GetParentsCategoriesResult']['Categories'];
        }

        return array();
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
     * Get the partner configuration result
     *
     * The Ricardo API returns:
     * <pre>
     * Array
     *   (
     *       [CurrencyId] => 2
     *       [CurrencyPrefix] => CHF
     *       [CurrencySuffix] =>
     *       [DataProtectionUrl] => http://www.ricardo.ch/ueber-uns/de-ch/reglemente.aspx
     *       [DecimalSeparator] => .
     *       [DefaultSellingDuration] => 7
     *       [DomainName] => ch.betaqxl.com
     *       [GrantedDescriptionTags] => Array
     *       (
     *           [0] => b
     *           [1] => br
     *           [2] => center
     *           [3] => ...
     *       )
     *       [LangIso] => DE
     *       [LanguageId] => 2
     *       [MaxRelistCount] => 9
     *       [MaxSellingDuration] => 10
     *       [MaximumPicturesToUpload] => 10
     *       [MinSellingDuration] => 1
     *       [MinimumAgeOnSite] => 18
     *       [PictureServer] => images.betaqxl.com
     *       [TermsConditionsUrl] => http://www.ricardo.ch/ueber-uns/de-ch/reglemente.aspx
     *   )
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getPartnerConfigurationsResult(array $data)
    {
        if (isset($data['GetPartnerConfigurationsResult'])) {
            return $data['GetPartnerConfigurationsResult'];
        }

        return array();
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
     * Get the payment conditions result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetPaymentConditionsResult": {
     *       "PaymentConditions": [{
     *         "PaymentConditionId": "INT",
     *         "PaymentConditionText": "TEXT",
     *         "PaymentMethods": "TEXT"
     *         }]
     *      }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getPaymentConditionsResult(array $data)
    {
        if (isset($data['GetPaymentConditionsResult']) && isset($data['GetPaymentConditionsResult']['PaymentConditions'])) {
            return $data['GetPaymentConditionsResult']['PaymentConditions'];
        }

        return array();
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
     *  Gets the payment conditions and payment function associated result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetPaymentConditionsResult": {
     *       "PaymentConditions": [{
     *         "PaymentConditionId": "INT",
     *         "PaymentConditionText": "TEXT",
     *         "PaymentMethods": "TEXT"
     *         }]
     *      }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getPaymentConditionsAndMethodsResult(array $data)
    {
        if (isset($data['GetPaymentConditionsAndMethodsResult']) && isset($data['GetPaymentConditionsAndMethodsResult']['PaymentConditions'])) {
            return $data['GetPaymentConditionsAndMethodsResult']['PaymentConditions'];
        }

        return array();
    }

    /**
     * Gets the payment methods.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        // $paymentConditionId has no effect on the Ricardo API side ! @fixme

        $onlyAllowToSell = true;
        $paymentConditionId = null;

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['only_allow_to_sell'])) ? $onlyAllowToSell = $args[0]['only_allow_to_sell'] : '';
            (isset($args[0]['payment_condition_id'])) ? $paymentConditionId = $args[0]['payment_condition_id'] : '';
        }

        return array(
            'method' => 'GetPaymentMethods',
            'params' => array('getPaymentMethodsParameter' => array('OnlyAllowToSell' => (int) $onlyAllowToSell, 'PaymentConditionId' => (int) $paymentConditionId ))
        );
    }

    /**
     * Gets the payment methods result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetPaymentMethodsResult": {
     *       "PaymentMethods": [{
     *         "PaymentMethodId": "INT",
     *         "PaymentMethodText": "TEXT"
     *         }]
     *      }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getPaymentMethodsResult(array $data)
    {
        if (isset($data['GetPaymentMethodsResult']) && isset($data['GetPaymentMethodsResult']['PaymentMethods'])) {
            return $data['GetPaymentMethodsResult']['PaymentMethods'];
        }

        return array();
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
     * Gets the phone prefixes result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetPhonePrefixesResult": {
     *       "AllTelCodes": [{
     *         "PaymentMethodId": "INT",
     *         "PaymentMethodText": "TEXT"
     *         }]
     *      }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getPhonePrefixesResult(array $data)
    {
        if (isset($data['GetPhonePrefixesResult']) && isset($data['GetPhonePrefixesResult']['AllTelCodes'])) {
            return $data['GetPhonePrefixesResult']['AllTelCodes'];
        }

        return array();
    }

    /**
     * Gets the promotions.
     *
     * @return array
     */
    public function getPromotions()
    {
        $articleStartDate = null;
        $articleType = 0;
        $categoryId = null;
        $displayMandatory = null;

        $args = func_get_args();
        if (!empty($args) && is_array($args[0])) {
            (isset($args[0]['only_allow_to_sell'])) ? $onlyAllowToSell = $args[0]['only_allow_to_sell'] : '';
            (isset($args[0]['payment_condition_id'])) ? $paymentConditionId = $args[0]['payment_condition_id'] : '';
        }

        return array(
            'method' => 'GetPromotions',
            'params' => array('getPromotionsParameter' => array(
                'ArticleStartDate' => $articleStartDate,
                'ArticleType' => $articleType,
                'CategoryId' => $categoryId,
                'DisplayMandatory' => $displayMandatory

            ))
        );
    }

    /**
     * Gets the phone prefixes result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetPromotionsResult": {
     *       "AllTelCodes": [{
     *         "PaymentMethodId": "INT",
     *         "PaymentMethodText": "TEXT"
     *         }]
     *      }
     *   }
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getPromotionsResult(array $data)
    {
        if (isset($data['GetPromotionsResult']) && isset($data['GetPromotionsResult']['Promotions'])) {
            return $data['GetPromotionsResult']['Promotions'];
        }

        return array();
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