<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin Magento Demo
 * @package     Diglin_
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo;

use \Diglin\Ricardo\Services\ServiceAbstract;
use \Diglin\Ricardo\Core\ApiInterface;

class ServiceManager
{
    protected $_services = array();

    protected $_api;

    private $_skipSecure = false;

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
        if (! ($service instanceof ServiceAbstract)) {
            $serviceName = $this->_getServiceName($service);
        }

        if (! ($service instanceof ServiceAbstract) && !isset($this->_services[$serviceName])) {
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

            $serviceMethod = $this->_prepareServiceGetMethod($serviceMethod);
            if (method_exists($serviceInstance, $serviceMethod)) {
                $service = $serviceInstance->$serviceMethod($arguments);
            }

            if (empty($service['method']) || empty($service['params'])) {
                throw new \Exception(printf('Method or parameters of the service %s cannot be empty', $serviceName));
            }

            // Implement get Token from here
            if (!$this->_skipSecure) {
                // @todo

                // we add the security service to the manager if not already done & get it
                /* @var $securityService \Diglin\Ricardo\Services\Security */
                $securityService = $this->add('security', true);

//                switch ($serviceInstance->getTypeOfToken())
//                {
//                    case ServiceAbstract::TOKEN_TYPE_IDENTIFIED:
//                        $temporaryCredential = '';
//
//                        $this->proceed('security', 'getTemporaryCredential');
//                        $this->proceed('security', 'getTokenCredential', $temporaryCredential);
//                        $tokenCredential = '';
//
//
//                        $this->getApi()->setUsername($tokenCredential);
//                        $this->getApi()->setShouldSetPass(false);
//                        break;
//                    case ServiceAbstract::TOKEN_TYPE_ANONYMOUS:
//                        $this->getApi()->setShouldSetPass(true);
//                        break;
//                    case ServiceAbstract::TOKEN_TYPE_DEFAULT:
//                        $this->getApi()->setShouldSetPass(true);
//                        break;
//                }


                $this->_skipSecure = false;
            }

            $data = $this->getApi()->connect($serviceInstance->getService(), $service['method'], $service['params']);

            $getResultServiceMethod = $this->_prepareServiceGetResultMethod($serviceMethod);

            if (method_exists($serviceInstance, $getResultServiceMethod)) {
                return $serviceInstance->$getResultServiceMethod($data);
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