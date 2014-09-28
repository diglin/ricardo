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