<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo;

use \Diglin\Ricardo\Core\ConfigInterface;

/**
 * Class Config
 *
 * Easy to understand :-)
 * Configuration class for the Ricardo API
 *
 * @package Diglin\Ricardo
 */
class Config extends \Zend_Config implements ConfigInterface
{
    /**
     * @param array $array
     * @param bool $allowModifications
     * @throws \Exception
     */
    public function __construct(array $array, $allowModifications = true)
    {
        parent::__construct($array, $allowModifications);

        if (!$this->getHost() || !$this->getPartnershipId() || !$this->getPartnershipPasswd())
        {
            throw new \Exception('Default Configuration values are missing: host, partnership ID or partnership Password. Please fix it!');
        }
    }
    /**
     * @return string
     */
    public function getHost()
    {
        return $this->get('host');
    }

    /**
     * Partnership ID and Username are the same but Ricardo
     * provides the partnership ID and you have to use it as a username in HTTP header
     *
     * @return string
     */
    public function getPartnershipId()
    {
        return $this->get('partnership_id');
    }

    /**
     * Clear password of the partnership.
     *
     * @return string
     */
    public function getPartnershipPasswd()
    {
        return $this->get('partnership_passwd');
    }
}