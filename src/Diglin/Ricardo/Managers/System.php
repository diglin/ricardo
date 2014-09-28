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

use Diglin\Ricardo\Enums\Article\PromotionCode;

/**
 * Class System
 * @package Diglin\Ricardo\Managers
 */
/**
 * Class System
 * @package Diglin\Ricardo\Managers
 */
class System extends ManagerAbstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'system';

    /**
     * @var array
     */
    protected $_errorCodes;

    /**
     * @var array
     */
    protected $_articleConditions;

    /**
     * @var array
     */
    protected $_availabilities;

    /**
     * @var array
     */
    protected $_categories;

    /**
     * @var array
     */
    protected $_category;

    /**
     * @var array
     */
    protected $_countries;

    /**
     * @var array
     */
    protected $_deliveryConditions;

    /**
     * @var array
     */
    protected $_firstChildsCategories;

    /**
     * @var array
     */
    protected $_languages;

    /**
     * @var array
     */
    protected $_packages;

    /**
     * @var array
     */
    protected $_parentsCategories;

    /**
     * @var array
     */
    protected $_partnerConfigurations;

    /**
     * @var array
     */
    protected $_paymentConditions;

    /**
     * @var array
     */
    protected $_paymentConditionsAndMethods;

    /**
     * @var array
     */
    protected $_paymentMethods;

    /**
     * @var
     */
    protected $_phonePrefixes;

    /**
     * @var
     */
    protected $_promotions;

    /**
     * @var
     */
    protected $_regions;

    /**
     * @var
     */
    protected $_templates;

    /**
     * @var
     */
    protected $_warranties;

    /**
     * @return array
     */
    public function getAllErrorsCodes()
    {
        if (empty($this->_errorCodes)) {
            $errors =  $this->_proceed('AllErrorsCodes');
            foreach ($errors as $error) {
                if (isset($error['NameSpace'])) {
                    $this->_errorCodes[$error['NameSpace']] = $error['Errors'];
                }
            }
        }
        return $this->_errorCodes;
    }

    /**
     * @param bool $isGroup
     * @return array
     */
    public function getArticleConditions($isGroup = false)
    {
        if (empty($this->_articleConditions)) {
            $this->_articleConditions = $this->_proceed('ArticleConditions', array('is_group' => $isGroup));
        }
        return $this->_articleConditions;
    }

    /**
     * @return array
     */
    public function getAvailabilities()
    {
        if (empty($this->_availabilities)) {
            $this->_availabilities = $this->_proceed('Availabilities');
        }
        return $this->_availabilities;
    }

    /**
     * @param int $categoryBrandingFilter
     * @param bool $onlyAllowToSell
     * @return array
     */
    public function getCategories($categoryBrandingFilter = 0, $onlyAllowToSell = true)
    {
        if (empty($this->_categories)) {
            $this->_categories = $this->_proceed('Categories', array('category_branding_filter' => (int) $categoryBrandingFilter, 'only_allow_to_sell' => (bool) $onlyAllowToSell));
        }
        return $this->_categories;
    }

    /**
     * @param $categoryId
     * @return array
     */
    public function getCategory($categoryId)
    {
        if (empty($this->_category)) {
            $this->_category = $this->_proceed('Category', $categoryId);
        }
        return $this->_category;
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        if (empty($this->_categories)) {
            $this->_categories = $this->_proceed('Countries');
        }
        return $this->_categories;
    }

    /**
     * @return array
     */
    public function getDeliveryConditions()
    {
        if (empty($this->_deliveryConditions)) {
            $this->_deliveryConditions = $this->_proceed('DeliveryConditions');
        }
        return $this->_deliveryConditions;
    }

    /**
     * @param $categoryId
     * @param int $categoryBrandingFilter
     * @param bool $onlyAllowToSell
     * @return array
     */
    public function getFirstChildsCategories($categoryId, $categoryBrandingFilter = 0, $onlyAllowToSell = true)
    {
        if (empty($this->_firstChildsCategories)) {
            $this->_firstChildsCategories = $this->_proceed('FirstChildsCategories',
                array(
                    'category_branding_filter' => $categoryBrandingFilter,
                    'only_allow_to_sell' => $onlyAllowToSell,
                    'category_id' => $categoryId
                )
            );
        }
        return $this->_firstChildsCategories;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        if (empty($this->_languages)) {
            $this->_languages = $this->_proceed('Languages');
        }
        return $this->_languages;
    }

    /**
     * @return array
     */
    public function getPackages()
    {
        if (empty($this->_packages)) {
            $this->_packages = $this->_proceed('Packages');
        }
        return $this->_packages;
    }

    /**
     * @param $categoryId
     * @return array
     */
    public function getParentsCategories($categoryId)
    {
        if (empty($this->_parentsCategories)) {
            $this->_parentsCategories = $this->_proceed('ParentsCategories', $categoryId);
        }
        return $this->_parentsCategories;
    }

    /**
     * @return array
     */
    public function getPartnerConfigurations()
    {
        if (empty($this->_partnerConfigurations)) {
            $this->_partnerConfigurations = $this->_proceed('PartnerConfigurations');
        }
        return $this->_partnerConfigurations;
    }

    /**
     * @param int|null $paymentMethodId
     * @return array
     */
    public function getPaymentConditions($paymentMethodId = null)
    {
        if (empty($this->_paymentConditions)) {
            $this->_paymentConditions = $this->_proceed('PaymentConditions', $paymentMethodId);
        }
        return $this->_paymentConditions;
    }

    /**
     * @return array
     */
    public function getPaymentConditionsAndMethods()
    {
        if (empty($this->_paymentConditionsAndMethods)) {
            $this->_paymentConditionsAndMethods = $this->_proceed('PaymentConditionsAndMethods');
        }
        return $this->_paymentConditionsAndMethods;
    }

    /**
     * @param bool $onlyAllowToSell
     * @param int $paymentConditionId
     * @return array
     */
    public function getPaymentMethods($onlyAllowToSell = true, $paymentConditionId = null)
    {
        if (empty($this->_paymentMethods)) {
            $this->_paymentMethods = $this->_proceed('PaymentMethods',
                array(
                    'only_allow_to_sell' => $onlyAllowToSell,
                    'payment_condition_id' => $paymentConditionId
                )
            );
        }
        return $this->_paymentMethods;
    }

    /**
     * @return mixed
     */
    public function getPhonePrefixes()
    {
        if (empty($this->_phonePrefixes)) {
            $this->_phonePrefixes = $this->_proceed('PhonePrefixes');
        }
        return $this->_phonePrefixes;
    }

    /**
     * @param string $articleStartDate
     * @param $articleType
     * @param int $categoryId
     * @param bool $displayMandatory
     * @return array
     */
    public function getPromotions($articleStartDate, $articleType, $categoryId, $displayMandatory)
    {
        if (empty($this->_promotions)) {
            $this->_promotions = $this->_proceed('Promotions',
                array(
                    'article_start_date' => $articleStartDate,
                    'article_type' => $articleType,
                    'category_id' => $categoryId,
                    'display_mandatory' => $displayMandatory
                )
            );

            foreach ($this->_promotions as &$promotion) {
                $promotion['PromotionLabel'] = PromotionCode::getLabel($promotion['PromotionId']);
            }
        }
        return $this->_promotions;
    }

    /**
     * @param int $countryId
     * @return array
     */
    public function getRegions($countryId)
    {
        if (empty($this->_regions)) {
            $this->_regions = $this->_proceed('Regions', array(
                'country_id' => $countryId
            ));
        }
        return $this->_regions;
    }

    /**
     * @return mixed
     */
    public function getTemplates()
    {
        if (empty($this->_templates)) {
            $this->_templates = $this->_proceed('Templates');
        }
        return $this->_templates;
    }

    /**
     * @return mixed
     */
    public function getWarranties()
    {
        if (empty($this->_warranties)) {
            $this->_warranties = $this->_proceed('Warranties');
        }
        return $this->_warranties;
    }

    /**
     * @param mixed $articleConditions
     * @return $this
     */
    public function setArticleConditions($articleConditions)
    {
        $this->_articleConditions = $articleConditions;
        return $this;
    }

    /**
     * @param mixed $availabilities
     * @return $this
     */
    public function setAvailabilities($availabilities)
    {
        $this->_availabilities = $availabilities;
        return $this;
    }

    /**
     * @param mixed $categories
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->_categories = $categories;
        return $this;
    }

    /**
     * @param mixed $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->_category = $category;
        return $this;
    }

    /**
     * @param mixed $countries
     * @return $this
     */
    public function setCountries($countries)
    {
        $this->_countries = $countries;
    }

    /**
     * @param mixed $deliveryConditions
     * @return $this
     */
    public function setDeliveryConditions($deliveryConditions)
    {
        $this->_deliveryConditions = $deliveryConditions;
    }

    /**
     * @param mixed $firstChildsCategories
     * @return $this
     */
    public function setFirstChildsCategories($firstChildsCategories)
    {
        $this->_firstChildsCategories = $firstChildsCategories;
        return $this;
    }

    /**
     * @param mixed $languages
     * @return $this
     */
    public function setLanguages($languages)
    {
        $this->_languages = $languages;
        return $this;
    }

    /**
     * @param mixed $packages
     * @return $this
     */
    public function setPackages($packages)
    {
        $this->_packages = $packages;
        return $this;
    }

    /**
     * @param mixed $parentsCategories
     * @return $this
     */
    public function setParentsCategories($parentsCategories)
    {
        $this->_parentsCategories = $parentsCategories;
        return $this;
    }

    /**
     * @param mixed $partnerConfiguration
     * @return $this
     */
    public function setPartnerConfiguration($partnerConfiguration)
    {
        $this->_partnerConfigurations = $partnerConfiguration;
        return $this;
    }

    /**
     * @param mixed $paymentConditions
     * @return $this
     */
    public function setPaymentConditions($paymentConditions)
    {
        $this->_paymentConditions = $paymentConditions;
        return $this;
    }

    /**
     * @param mixed $paymentConditionsAndMethods
     * @return $this
     */
    public function setPaymentConditionsAndMethods($paymentConditionsAndMethods)
    {
        $this->_paymentConditionsAndMethods = $paymentConditionsAndMethods;
        return $this;
    }

    /**
     * @param array $paymentMethods
     * @return $this
     */
    public function setPaymentMethods($paymentMethods)
    {
        $this->_paymentMethods = $paymentMethods;
        return $this;
    }

    /**
     * @param array $partnerConfigurations
     * @return $this
     */
    public function setPartnerConfigurations($partnerConfigurations)
    {
        $this->_partnerConfigurations = $partnerConfigurations;
        return $this;
    }

    /**
     * @param mixed $phonePrefixes
     * @return $this
     */
    public function setPhonePrefixes($phonePrefixes)
    {
        $this->_phonePrefixes = $phonePrefixes;
        return $this;
    }

    /**
     * @param mixed $promotions
     * @return $this
     */
    public function setPromotions($promotions)
    {
        $this->_promotions = $promotions;
        return $this;
    }

    /**
     * @param mixed $regions
     * @return $this
     */
    public function setRegions($regions)
    {
        $this->_regions = $regions;
        return $this;
    }

    /**
     * @param mixed $templates
     * @return $this
     */
    public function setTemplates($templates)
    {
        $this->_templates = $templates;
        return $this;
    }

    /**
     * @param mixed $warranties
     * @return $this
     */
    public function setWarranties($warranties)
    {
        $this->_warranties = $warranties;
        return $this;
    }
}