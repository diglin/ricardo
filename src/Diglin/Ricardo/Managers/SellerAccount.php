<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Managers;

class SellerAccount extends ManagerAbstract
{
    public function getTemplates()
    {
        $result = $this->_serviceManager->proceed('seller_account', 'getTemplates');
    }
}