<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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