<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Exception
 */
class Diglin_Ricento_Exception extends Mage_Core_Exception
{
    /**
     * @var bool
     */
    protected $_needAuthorization = false;

    /**
     * @var string
     */
    protected $_validationUrl = '';

    /**
     * @param boolean $needAuthorization
     * @return $this
     */
    public function setNeedAuthorization($needAuthorization)
    {
        $this->_needAuthorization = $needAuthorization;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getNeedAuthorization()
    {
        return $this->_needAuthorization;
    }

    /**
     * @param string $validationUrl
     * @return $this
     */
    public function setValidationUrl($validationUrl)
    {
        $this->_validationUrl = $validationUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->_validationUrl;
    }
}