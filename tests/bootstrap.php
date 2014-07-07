<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__DIR__) . '/tests/src'));

require_once __DIR__ . '/../src/SplAutoloader.php';
$autoload = new SplAutoloader(null, realpath(dirname(__DIR__) . '/src'));
$autoload->register();