<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin Magento Demo
 * @package     Diglin_
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__DIR__) . '/src'));

require '../src/Diglin/Ricardo/SplAutoloader.php';
$autoload = new SplAutoloader();
$autoload->register();

//require '../Api.php';
//require '../Config.php';
//require '../Security.php';

// Ricardo API for German version
$configParams = array(
    'host' => 'ws.betaqxl.com',
    'partnership_id' => '0F43A456-187E-4494-B4CC-3AC03AA70C90',
    'partnership_passwd' => 'PassSenghor!!!',
    'partner_url' => 'http://www.diglin.com/',
    'debug' => true
);

$config = new \Diglin\Ricardo\Config($configParams);
$api = new  \Diglin\Ricardo\Api($config);
$serviceManager = new \Diglin\Ricardo\ServiceManager($api);

$securityManager = new \Diglin\Ricardo\SecurityManager($serviceManager);

//echo $securityManager->getToken(\Diglin\Ricardo\Services\ServiceAbstract::TOKEN_TYPE_ANONYMOUS);

echo $securityManager->getToken(\Diglin\Ricardo\Services\ServiceAbstract::TOKEN_TYPE_IDENTIFIED);

print_r($api->getLastDebug());