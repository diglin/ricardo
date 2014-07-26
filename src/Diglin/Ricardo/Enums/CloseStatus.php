<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    magento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Enums;

abstract class CloseStatus
{
    // Open article
    const Open = 0;

    // Closed article
    const Closed = 1;

    // Closed by customer
    const ClosedByCustomer = 2;
}