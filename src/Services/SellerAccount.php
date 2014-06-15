<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo\Services;

use \Diglin\Ricardo\Services\ServiceAbstract;

/**
 * Class SellerAccount
 *
 * Refers to the account as a seller:
 * get all open articles, get sold articles, get articles that haven't been sold
 *
 * @package Diglin\Ricardo
 */
class SellerAccount extends ServiceAbstract
{
    protected $_service = 'SellerAccountService';

    protected $_typeOfToken = self::TOKEN_TYPE_IDENTIFIED;

    public function getTemplates()
    {
        return array(
            'method' => 'GetTemplates',
            'params' => array()
        );
    }
}