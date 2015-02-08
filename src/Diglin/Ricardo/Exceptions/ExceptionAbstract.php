<?php
/**
 * Diglin GmbH - Switzerland
 *
 * This file is part of a Diglin GmbH module.
 *
 * This Diglin GmbH module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */

namespace Diglin\Ricardo\Exceptions;

/**
 * Class ExceptionAbstract
 * @package Diglin\Ricardo\Exceptions
 */
class ExceptionAbstract extends \Exception
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $message = $this->_getMessage($code, $message);
        return parent::__construct($message, $code, $previous);
    }

    /**
     * @param $code
     * @param string $defaultMessage
     * @return bool|string
     */
    protected function _getMessage($code, $defaultMessage = '')
    {
        $class = get_class($this);
        $class = str_replace('Exceptions', 'Enums', $class);
        $class = str_replace('Exception', 'Errors', $class);

        if (class_exists($class)) {
            /* @var $class \Diglin\Ricardo\Enums\AbstractEnums */
            $message = $class::getLabel($code);
            if (!empty($message)) {
                return $defaultMessage . ' ' . $message;
            }
        }

        return $defaultMessage;
    }
}
