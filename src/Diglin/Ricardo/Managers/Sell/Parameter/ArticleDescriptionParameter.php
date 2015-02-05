<?php
/**
 * Diglin GmbH - Switzerland
 *
 * This file is part of a Diglin GmbH module.
 *
 * This Diglin GmbH module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
namespace Diglin\Ricardo\Managers\Sell\Parameter;

use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class ArticleInformationParameter
 * @package Diglin\Ricardo\Managers\Sell
 */
class ArticleDescriptionParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_articleDescription; // required

    /**
     * @var string
     */
    protected $_articleSubtitle; // optional

    /**
     * @var string
     */
    protected $_articleTitle; // required

    /**
     * @var string
     */
    protected $_deliveryDescription; // optional

    /**
     * Enums Diglin\Ricardo\Enums\System\LanguageCode
     *
     * @var int
     */
    protected $_languageId; // required

    /**
     * @var string
     */
    protected $_paymentDescription; // optional

    /**
     * @var string
     */
    protected $_warrantyDescription; // optional

    protected $_requiredProperties = array(
        'articleDescription',
        'articleTitle',
        'languageId',
    );

    protected $_optionalProperties = array(
        'articleSubtitle',
        'deliveryDescription',
        'paymentDescription',
        'warrantyDescription',
    );

    /**
     * @param mixed $articleDescription
     * @return $this
     */
    public function setArticleDescription($articleDescription)
    {
        $this->_articleDescription = $articleDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticleDescription()
    {
        return $this->_articleDescription;
    }

    /**
     * @param mixed $articleSubtitle
     * @return $this
     */
    public function setArticleSubtitle($articleSubtitle)
    {
        $this->_articleSubtitle = $articleSubtitle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticleSubtitle()
    {
        return $this->_articleSubtitle;
    }

    /**
     * @param mixed $articleTitle
     * @return $this
     */
    public function setArticleTitle($articleTitle)
    {
        $this->_articleTitle = $articleTitle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticleTitle()
    {
        return $this->_articleTitle;
    }

    /**
     * @param mixed $deliveryDescription
     * @return $this
     */
    public function setDeliveryDescription($deliveryDescription)
    {
        $this->_deliveryDescription = $deliveryDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDescription()
    {
        return $this->_deliveryDescription;
    }

    /**
     * @param int $languageId
     * @return $this
     */
    public function setLanguageId($languageId)
    {
        if (in_array($languageId, \Diglin\Ricardo\Enums\System\LanguageCode::getValues())) {
            $this->_languageId = (int) $languageId;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getLanguageId()
    {
        return $this->_languageId;
    }

    /**
     * @param mixed $paymentDescription
     * @return $this
     */
    public function setPaymentDescription($paymentDescription)
    {
        $this->_paymentDescription = $paymentDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentDescription()
    {
        return $this->_paymentDescription;
    }

    /**
     * @param mixed $warrantyDescription
     * @return $this
     */
    public function setWarrantyDescription($warrantyDescription)
    {
        $this->_warrantyDescription = $warrantyDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWarrantyDescription()
    {
        return $this->_warrantyDescription;
    }
}
