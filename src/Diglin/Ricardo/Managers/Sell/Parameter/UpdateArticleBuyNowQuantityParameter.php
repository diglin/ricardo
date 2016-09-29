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
 * Class UpdateArticleBuyNowQuantityParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class UpdateArticleBuyNowQuantityParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_antiforgeryToken; // required

    /**
     * @var int
     */
    protected $_quantity; // required

    /**
     * @var string
     */
    protected $_articleId; // required

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'antiforgeryToken',
        'quantity',
        'articleId'
    );

    protected $_optionalProperties = [];

    /**
     * @return string
     */
    public function getAntiforgeryToken()
    {
        return $this->_antiforgeryToken;
    }

    /**
     * @param string $antiforgeryToken
     * @return $this
     */
    public function setAntiforgeryToken($antiforgeryToken)
    {
        $this->_antiforgeryToken = $antiforgeryToken;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleId()
    {
        return $this->_articleId;
    }

    /**
     * @param string $articleId
     * @return $this
     */
    public function setArticleId($articleId)
    {
        $this->_articleId = $articleId;

        return $this;
    }
}
