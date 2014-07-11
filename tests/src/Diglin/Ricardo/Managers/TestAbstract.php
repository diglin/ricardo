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

use \Diglin\Ricardo\Config;
use \Diglin\Ricardo\Api;
use \Diglin\Ricardo\Service;

abstract class TestAbstract extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Service
     */
    protected $_serviceManager;

    protected function tearDown()
    {
        $this->_serviceManager = null;
        parent::tearDown();
    }

    /**
     * @return Service
     * @throws \Exception
     */
    protected function getServiceManager()
    {
        if (empty($this->_serviceManager)) {
            $configIniFile = __DIR__ . '/../../../../conf/config.ini';
            if (!file_exists($configIniFile)) {
                throw new \Exception('Missing Config.ini file');
            }

            $config = parse_ini_file(__DIR__ . '/../../../../conf/config.ini', true);

            if (isset($config['GERMAN'])) {
                $ricardoConfig = new Config($config['GERMAN']);
                $api = new Api($ricardoConfig);
                $this->_serviceManager = new Service($api);
            } else {
                throw new \Exception('Missing GERMAN section in the ini file');
            }
        }

        return $this->_serviceManager;
    }

    protected function getLastApiDebug($flush = true)
    {
        return print_r($this->getServiceManager()->getApi()->getLastDebug($flush), true);
    }
}