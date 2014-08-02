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