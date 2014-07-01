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
        return new Helper();
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

        return $this->_serviceManager->proceed($this->_serviceName, $method, $parameters);
    }
}