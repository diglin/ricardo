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
 * Class AddArticlePicturesParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class AddArticlePicturesParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_antiforgeryToken; // required

    /**
     * @var int
     */
    protected $_articleId; // required

    /**
     * @var array
     */
    protected $_pictures = array(); // required

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'antiforgeryToken',
        'articleId',
        'pictures',
    );

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
     * @return string
     */
    public function getAntiforgeryToken()
    {
        return $this->_antiforgeryToken;
    }

    /**
     * @param int $articleId
     * @return $this
     */
    public function setArticleId($articleId)
    {
        $this->_articleId = $articleId;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->_articleId;
    }

    /**
     * @param \Diglin\Ricardo\Managers\Sell\Parameter\ArticlePictureParameter $pictures
     * @param bool $clear
     * @return $this
     */
    public function setPictures(ArticlePictureParameter $pictures, $clear = false)
    {
        if ($clear) {
            $this->_pictures = array();
        }
        $this->_pictures[] = $pictures;
        return $this;
    }

    /**
     * @return array of \Diglin\Ricardo\Managers\Sell\Parameter\ArticlePictureParameter
     */
    public function getPictures()
    {
        return $this->_pictures;
    }
}
