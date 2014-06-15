<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo\Core;

interface ConfigInterface
{
    public function getHost();
    public function getPartnershipId();
    public function getPartnershipPasswd();
}