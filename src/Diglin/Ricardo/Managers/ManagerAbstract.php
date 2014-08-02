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

use \Diglin\Ricardo\Core\Helper;
use \Diglin\Ricardo\Service;

/**
 * Class ManagerAbstract
 *
 * @package Diglin\Ricardo\Managers
 */
abstract class ManagerAbstract
{
    /**
     * @var string
     */
    protected $_serviceName;

    /**
     * @var Service
     */
    protected $_serviceManager;

    /**
     * @var Helper
     */
    protected $_helper;

    /**
     * @param Service $serviceManager
     */
    public function __construct(Service $serviceManager)
    {
        $this->_serviceManager = $serviceManager;
    }

    /**
     * @return Helper
     */
    public function getHelper()
    {
        if (!$this->_helper) {
            $this->_helper = new Helper();
        }
        return $this->_helper;
    }

    /**
     * @param string $method
     * @param string|null|array $parameters
     * @return array
     * @throws \Exception
     */
    protected function _proceed($method, $parameters = null)
    {
        if (!$this->_serviceName) {
            throw new \Exception('Service Name missing to proceed from Manager');
        }

        $result = $this->_serviceManager->proceed($this->_serviceName, $method, $parameters);
        $this->extractError($result);

        return $result;
    }

    /**
     * @param array $result
     */
    protected function extractError(array $result)
    {
        if (!empty($result['ErrorCodes']) && isset($result['ErrorCodesType'])) {
            $classname = '\\Diglin\\Ricardo\\Exceptions\\' . $result['ErrorCodesType'];
            $tmp = $result;
            $errorCode =  array_shift($tmp['ErrorCodes']);

            if (!class_exists($classname)) {
                $classname = '\Exception';
            }
            throw new $classname(print_r($result, true), $errorCode['Key']);
        }
    }

    /**
     * @return Service
     */
    public function getServiceManager()
    {
        return $this->_serviceManager;
    }
}