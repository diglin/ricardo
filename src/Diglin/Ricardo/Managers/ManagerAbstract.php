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

use Diglin\Ricardo\Core\Helper;
use Diglin\Ricardo\Service;
use Diglin\Ricardo\Exceptions\ExceptionAbstract;
use Diglin\Ricardo\Exceptions\SecurityErrors;
use Diglin\Ricardo\Enums\SecurityErrors as SecurityErrorsEnum;

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
     * @var Helper
     */
    protected $_helper;

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
        if (!$this->_helper) {
            $this->_helper = new Helper();
        }
        return $this->_helper;
    }

    /**
     * @param string $method
     * @param string|null|array $parameters
     * @return array
     * @throws \Exception
     * @throws SecurityErrors
     */
    protected function _proceed($method, $parameters = null)
    {
        if (!$this->_serviceName) {
            throw new ExceptionAbstract('Service Name missing to proceed from Manager');
        }

        $result = $this->_serviceManager->proceed($this->_serviceName, $method, $parameters);

        try {
            $this->extractError($result);
        } catch (SecurityErrors $e) {
            switch ($e->getCode()) {
                case SecurityErrorsEnum::SESSIONEXPIRED:
                    $this->_serviceManager->getSecurityManager()->refreshToken();
                    $result = self::_proceed($method, $parameters);
                    break;
                case SecurityErrorsEnum::TOKENERROR:
                case SecurityErrorsEnum::TOKENEXPIRED:
                case SecurityErrorsEnum::TEMPORAYCREDENTIALUNVALIDATED:
                    // We init the validation url to be used later in any process (e.g. re-authorization)
                    $this->_serviceManager->getSecurityManager()->getValidationUrl(true);
                    throw new SecurityErrors('Token Credential must be recreated! Please, authorize again the access to the Ricardo API', SecurityErrorsEnum::TOKEN_AUTHORIZATION);
                    break;
                case SecurityErrorsEnum::TEMPORAYCREDENTIALEXPIRED:
                    $this->_serviceManager->getSecurityManager()->getTemporaryToken(true);
                    $result = self::_proceed($method, $parameters);
                    break;
                default:
                    throw $e;
                    break;
            }
            // @todo detect ErrorCodesType Technical and throw Exception to the user (happened when trying to get anonymous token at 8:17 12.08.2014)
        }

        return $result;
    }

    /**
     * Extract code error and type from API call
     *
     * @param array $result
     * @throw \Exception
     */
    protected function extractError(array $result)
    {
        if (!empty($result['ErrorCodes']) && isset($result['ErrorCodesType'])) {

            $errorCodeType = $result['ErrorCodesType'];
            $errorCode = array_shift($result['ErrorCodes']);

            $classname = '\Diglin\Ricardo\Exceptions\\' . $errorCodeType;
            if (!class_exists($classname)) {
                $classname = '\Exception';
            }

            throw new $classname($errorCodeType, $errorCode['Key']);
        }
    }

    /**
     * @return Service
     */
    public function getServiceManager()
    {
        return $this->_serviceManager;
    }

    /**
     * @return string
     */
    public function getTypeOfToken()
    {
        return $this->_serviceManager->get($this->_serviceName)->getTypeOfToken();
    }
}