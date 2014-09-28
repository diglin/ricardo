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

namespace Diglin\Ricardo\Core;

interface ApiInterface
{
    /**
     * Connect to the API
     *
     * @param string $service
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function connect($service, $method, array $params);

    /**
     * Get the config of the API
     *
     * @return mixed
     */
    public function getConfig();

    /**
     * Set if the pass should be sent or not
     *
     * @param boolean $boolean
     * @return mixed
     */
    public function setShouldSetPass($boolean);

    /**
     * Get if the pass should be sent or not
     *
     * @return mixed
     */
    public function getShouldSetPass();
}