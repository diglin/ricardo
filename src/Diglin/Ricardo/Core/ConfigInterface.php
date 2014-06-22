<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Core;

interface ConfigInterface
{
    /**
     * Get the Ricardo host configuration
     *
     * @return mixed
     */
    public function getHost();

    /**
     * Get the Partnership ID configuration
     *
     * @return mixed
     */
    public function getPartnershipId();

    /**
     * Get the Partnership Password configuration
     *
     * @return mixed
     */
    public function getPartnershipPasswd();
}