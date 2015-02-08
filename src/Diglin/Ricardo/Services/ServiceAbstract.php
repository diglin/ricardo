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
namespace Diglin\Ricardo\Services;

/**
 * Class ServiceAbstract
 *
 * @package Diglin\Ricardo\Services
 */
abstract class ServiceAbstract
{
    const TOKEN_TYPE_ANONYMOUS = 'anonymous';
    const TOKEN_TYPE_IDENTIFIED = 'identified';
    const TOKEN_TYPE_ANTIFORGERY = 'antiforgery';
    const TOKEN_TYPE_TEMPORARY = 'temporary';
    const TOKEN_TYPE_DEFAULT = '';

    /**
     * @var string
     */
    protected $_service = '';

    /**
     * @var string
     */
    protected $_typeOfToken = self::TOKEN_TYPE_ANONYMOUS;

    /**
     * Get the current name of the API service
     *
     * @return string
     */
    public function getService()
    {
        return $this->_service;
    }

    /**
     * Get the type of token needed for this service
     * <pre>
     * It can be:
     * - anonymous
     * - identified
     * - antiforgery
     * - empty
     * </pre>
     *
     * @return string
     */
    public function getTypeOfToken()
    {
        return $this->_typeOfToken;
    }
}
