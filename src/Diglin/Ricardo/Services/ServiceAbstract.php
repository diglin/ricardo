<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin Magento Demo
 * @package     Diglin_
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo\Services;

abstract class ServiceAbstract
{
    const TOKEN_TYPE_ANONYMOUS = 'anonymous';
    const TOKEN_TYPE_IDENTIFIED = 'identified';
    const TOKEN_TYPE_DEFAULT = '';

    protected $_service = '';

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