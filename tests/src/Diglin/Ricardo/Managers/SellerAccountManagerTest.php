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

class SellerAccountManagerTest extends TestAbstract
{
    public function testGetTemplates()
    {
        $result = $this->getServiceManager()->proceed('seller_account', 'Templates');

        $this->assertGreaterThanOrEqual(1, count($result), 'Number of templates found is not greater than 1');
        //print_r($this->getServiceManager()->getApi()->getLastDebug());
    }
}