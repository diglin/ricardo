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

use \Diglin\Ricardo\Services\ServiceAbstract;
use \Diglin\Ricardo\Core\ApiInterface;
use \Diglin\Ricardo\Managers\Security;

/**
 * Class Service
 *
 * @package Diglin\Ricardo\Managers
 */
class Service
{
    /**
     * @var array
     */
    protected $_services = array();

    /**
     * @var ApiInterface
     */
    protected $_api;

    /**
     * @var Security
     */
    protected $_securityManager;

    /**
     * @param ApiInterface $api
     */
    public function __construct(ApiInterface $api)
    {
        $this->_api = $api;
    }

    /**
     * Get the API class
     *
     * @return ApiInterface
     */
    public function getApi()
    {
        return $this->_api;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->_api->getConfig();
    }

    /**
     * Get all services
     *
     * @return array
     */
    public function getServices()
    {
        return $this->_services;
    }

    /**
     * Add a service to the service manager
     *
     * @param string|ServiceAbstract $service
     * @param bool $return
     * @return $this
     */
    public function add($service, $return = false)
    {
        if (!($service instanceof ServiceAbstract)) {
            $serviceName = $this->_getServiceName($service);
        } else {
            $serviceName = $service->getService();
        }

        if (!($service instanceof ServiceAbstract) && !isset($this->_services[$serviceName])) {
            $serviceClass = '\Diglin\Ricardo\Services\\'. $this->_getCleanServiceClassName($service);
            if (class_exists($serviceClass)) {
                $service = new $serviceClass();
            }
        }

        if ($service instanceof ServiceAbstract && !isset($this->_services[$serviceName])) {
            $this->_services[$service->getService()] = $service;
        }

        if ($return && isset($this->_services[$serviceName])) {
            return $this->_services[$serviceName];
        }

        return $this;
    }

    /**
     * Get the instance of a specific service or get an array of available services
     *
     * @param string $serviceName
     * @return bool
     */
    public function get($serviceName)
    {
        $serviceName = $this->_getServiceName($serviceName);

        if (isset($this->_services[$serviceName]) && $this->_services[$serviceName] instanceof ServiceAbstract) {
            return $this->_services[$serviceName];
        }
        return false;
    }

    /**
     * Update the service into the service manager in case of properties changes for example
     *
     * @param ServiceAbstract $service
     * @return $this
     */
    public function update(ServiceAbstract $service)
    {
        if (isset($this->_services[$service->getService()])) {
            $this->_services[$service->getService()] = $service;
        }
        return $this;
    }

    /**
     * Remove a service from the service manager to free some memory
     *
     * @param string $serviceName
     * @return $this
     */
    public function remove($serviceName)
    {
        $serviceName = $this->_getServiceName($serviceName);

        if (isset($this->_services[$serviceName])) {
            $this->_services[$serviceName] = null;
            unset($this->_services[$serviceName]);
        }
        return $this;
    }

    /**
     * @return \Diglin\Ricardo\Managers\Security
     */
    public function getSecurityManager()
    {
        if (!$this->_securityManager) {
            $this->_securityManager = new Security($this, $this->getConfig()->getAllowValidationUrl());
        }
        return $this->_securityManager;
    }

    /**
     * Execute a method on the service and return an array
     *
     * @param string $serviceName
     * @param string $serviceMethod
     * @param array|string|null $arguments
     * @return mixed
     * @throws \Exception
     */
    public function proceed($serviceName, $serviceMethod, $arguments = null)
    {
        $serviceInstance = $this->add($serviceName, true);

        if ($serviceInstance instanceof ServiceAbstract) {
            $service = array();

            $serviceMethodGet = $this->_prepareServiceGetMethod($serviceMethod);
            if (method_exists($serviceInstance, $serviceMethodGet)) {
                $service = $serviceInstance->$serviceMethodGet($arguments);
                $serviceMethod = $serviceMethodGet;
            } elseif (method_exists($serviceInstance, $serviceMethod)) {
                $service = $serviceInstance->$serviceMethod($arguments);
            }

            if (empty($service['method'])) {
                throw new \Exception(printf('Method "%s" of the service "%s" cannot be empty', $service['method'], $serviceName));
            }

            if (!$this->_securityManager) {
                $this->_securityManager = $this->getSecurityManager();
            }

            switch ($serviceInstance->getTypeOfToken())
            {
                case ServiceAbstract::TOKEN_TYPE_IDENTIFIED:
                    $token = $this->_securityManager->getToken(ServiceAbstract::TOKEN_TYPE_IDENTIFIED);
                    $this->getApi()
                        ->setUsername($token)
                        ->setShouldSetPass(false);
                    break;
                case ServiceAbstract::TOKEN_TYPE_ANTIFORGERY:
                    $token = $this->_securityManager->getToken(ServiceAbstract::TOKEN_TYPE_ANTIFORGERY);
                    $this->getApi()
                        ->setUsername($token)
                        ->setShouldSetPass(false);
                    break;
                case ServiceAbstract::TOKEN_TYPE_ANONYMOUS:
                    $token = $this->_securityManager->getToken(ServiceAbstract::TOKEN_TYPE_ANONYMOUS);
                    $this->getApi()
                        ->setUsername($token)
                        ->setShouldSetPass(false);
                    break;
                case ServiceAbstract::TOKEN_TYPE_DEFAULT:
                default:
                    $this->getApi()
                        ->revertUsername()
                        ->setShouldSetPass(true);
                    break;
            }

            $data = $this->getApi()->connect($serviceInstance->getService(), $service['method'], $service['params']);

            //@todo Manage errors - provide exception related to the service and its error code
            if ($data && array_key_exists('ErrorCodes', $data)) {
                return $data;
                //throw new \Exception('Ricardo API Returned Errors . ' . print_r($data, true), (isset($data['ErrorCodes']) ? $data['ErrorCodes'][0]['Key'] : array()));
            }

            $getResultServiceMethod = $this->_prepareServiceGetResultMethod($serviceMethod);

            if (method_exists($serviceInstance, $getResultServiceMethod)) {
                return $serviceInstance->$getResultServiceMethod( (array) $data);
            } else {
                return $data;
            }

        } else {
            throw new \Exception(printf('%s is not an instance of ServiceAbstract or does not exists.', $serviceName));
        }
    }

    /**
     * Try to prevent some errors with the service name
     * So replace all underscore or space in a service name
     *
     * @param string $serviceClassName
     * @return mixed
     */
    protected function _getCleanServiceClassName($serviceClassName)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ',$serviceClassName)));
    }

    /**
     * Retrieve the correct service class name
     *
     * @param string $serviceName
     * @return mixed|string
     */
    protected function _getServiceName($serviceName)
    {
        $serviceName = $this->_getCleanServiceClassName($serviceName);
        if (!strpos($serviceName, 'Service')) {
            $serviceName .= 'Service';
        }
        return $serviceName;
    }

    /**
     * Add 'get' to the method name
     *
     * @param string $method
     * @return string
     */
    protected function _prepareServiceGetMethod($method)
    {
        if (strpos(strtolower($method), 'get') === false) {
            $method = 'get' . ucfirst($method);
        }
        return $method;
    }

    /**
     * Add 'get' to the method name and 'Result' at the end of the name
     *
     * @param $method
     * @return string
     */
    protected function _prepareServiceGetResultMethod($method)
    {
        if (strpos($method, 'Result') === false) {
            $method .= 'Result';
        }
        return $method;
    }
}