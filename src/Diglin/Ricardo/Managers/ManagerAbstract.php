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
namespace Diglin\Ricardo\Managers;

use Diglin\Ricardo\Core\Helper;
use Diglin\Ricardo\Service;
use Diglin\Ricardo\Exceptions\ExceptionAbstract;
use Diglin\Ricardo\Exceptions\SecurityException;
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
     * @param mixed $parameters
     * @return array
     * @throws \Exception
     * @throws SecurityException
     */
    protected function _proceed($method, $parameters = null)
    {
        if (!$this->_serviceName) {
            throw new ExceptionAbstract('Service Name missing to proceed from Manager');
        }

        $result = $this->_serviceManager->proceed($this->_serviceName, $method, $parameters);

        if (is_null($result)) {
            throw new \Exception($this->_serviceManager->getApi()->getLastDebug());
        }

        try {
            $this->extractError((array) $result);
        } catch (SecurityException $e) {
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
                    throw new SecurityException('Token Credential must be recreated! Please, authorize again the access to the Ricardo API. Original Error Code: '. $e->getCode(), SecurityErrorsEnum::TOKEN_AUTHORIZATION);
                    break;
                case SecurityErrorsEnum::TEMPORAYCREDENTIALEXPIRED:
                    $this->_serviceManager->getSecurityManager()->getTemporaryToken(true);
                    $result = self::_proceed($method, $parameters);
                    break;
                default:
                    throw $e;
                    break;
            }
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
        if (isset($result['ErrorCodesType'])) {

            if (count($result['ErrorCodes']) > 1) {
                //@todo handle when several errors code are returned e.g. by inserting an article
            }

            $errorCodeType = $result['ErrorCodesType'];
            $errorType = $result['ErrorType'];

            if (isset($result['ErrorCodes'])) {
                $errorCode = array_shift($result['ErrorCodes']);
            }

            if (empty($errorCode)) {
                $errorCode = array('Key' => null);
                $errorCodeType .= ' ' . $errorType;
            }

            $classname = '\Diglin\Ricardo\Exceptions\\' . $errorType;
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