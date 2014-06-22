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
 * Class SellerAccount
 *
 * Refers to the account as a seller:
 * get all open articles, get sold articles, get articles that haven't been sold
 *
 * @package Diglin\Ricardo\Services
 */
class SellerAccount extends ServiceAbstract
{
    /**
     * @var string
     */
    protected $_service = 'SellerAccountService';

    /**
     * @var string
     */
    protected $_typeOfToken = self::TOKEN_TYPE_IDENTIFIED;

    /**
     * Get available article templates
     *
     * @return array
     */
    public function getTemplates()
    {
        return array(
            'method' => 'GetTemplates',
            'params' => array()
        );
    }

    /**
     * Get the list of templates available
     *
     * @param array
     * @return array
     */
    public function getTemplatesResult($data)
    {
        if (isset($data['GetTemplatesResult']) && isset($data['GetTemplatesResult']['Templates'])) {
            return $data['GetTemplatesResult']['Templates'];
        }
        return array();
    }
}