<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__DIR__) . '/tests/src'));

require_once __DIR__ . '/../src/SplAutoloader.php';
$autoload = new SplAutoloader(null, realpath(dirname(__DIR__) . '/src'));
$autoload->register();