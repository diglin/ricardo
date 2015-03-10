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
 * Class ArticlesDetails
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class GetArticlesFeeParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_articlesDetails; // required

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'articlesDetails'
    );

    /**
     * @param GetArticleFeeParameter $articleDetail
     * @param bool $clear
     * @return $this
     */
    public function setArticlesDetails(GetArticleFeeParameter $articleDetail = null, $clear = false)
    {
        if ($clear) {
            $this->_articlesDetails = array();
        }

        if (is_null($articleDetail)) {
            return $this;
        }

        $this->_articlesDetails [] = $articleDetail;
        return $this;
    }

    /**
     * @return array
     */
    public function getArticlesDetails()
    {
        return $this->_articlesDetails ;
    }
}
