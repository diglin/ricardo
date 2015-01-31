<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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