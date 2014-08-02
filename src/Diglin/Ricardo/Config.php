<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
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
            throw new \Exception(
                sprintf('Default Configuration values are missing: host %s, partnership ID %s or partnership Password %s. Please fix it!', $this->getHost(), $this->getPartnershipId(), $this->getPartnershipPasswd())
            );
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

    /**
     * Get if we must simulate an authorization or not to Ricardo
     *
     * @return bool
     */
    public function getAllowValidationUrl()
    {
        return (bool) $this->get('allow_authorization_simulation');
    }

    /**
     * Get the username configuration of the customer
     *
     * @return string
     */
    public function getCustomerUsername()
    {
        return $this->get('customer_username');
    }

    /**
     * Get the password configuration of the customer
     *
     * @return string
     */
    public function getCustomerPassword()
    {
        return $this->get('customer_password');
    }

    /**
     * Get the partner url
     *
     * @return mixed
     */
    public function getPartnerUrl()
    {
        return $this->get('partner_url');
    }
}