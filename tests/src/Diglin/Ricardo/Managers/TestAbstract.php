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
     * Init the Service Manager to allow mapping between class managers and API services
     *
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
                $this->_serviceManager = new Service(new Api(new Config($config['GERMAN'])));
            } else {
                throw new \Exception('Missing GERMAN section in the ini file');
            }
        }

        return $this->_serviceManager;
    }

    /**
     * Get the last API Curl request for debug purpose
     *
     * @param bool $flush
     * @param bool $return
     * @param bool $log
     * @return mixed
     */
    protected function getLastApiDebug($flush = true, $return = true, $log = false)
    {
        $content = print_r($this->getServiceManager()->getApi()->getLastDebug($flush), true);

        if ($log) {
            $this->log($content);
        }

        if ($return) {
            return $content;
        }
    }

    /**
     * Get the content of some test variable
     *
     * @param array|int|string $output
     * @param string $testName
     * @param bool $debug
     */
    protected function outputContent($output, $testName = '', $debug = false)
    {
        if ($this->getServiceManager()->getConfig()->get('display_test_content')) {
            echo $testName . ' ' . print_r($output, true);
        }
        if ($debug) {
            echo $this->getLastApiDebug(false, false, true);
        }
        return;
    }

    protected function log($content)
    {
        $filename = __DIR__ . '/../../../../log/api.log';
        $handle = fopen($filename, 'w+');

        $time = date('Y-m-d H:i:s') . "\n";
        $content = $time . $content;

        fwrite($handle, $content);
        fclose($handle);

        return $this;
    }
}