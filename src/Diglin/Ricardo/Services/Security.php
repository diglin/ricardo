<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo\Services;

use \Diglin\Ricardo\Services\ServiceAbstract;

/**
 * Class Security
 *
 * Ricardo SecurityService API
 * Manage Token generation
 *
 * @package Diglin\Ricardo
 */
class Security extends ServiceAbstract
{
    protected $_service = 'SecurityService';

    protected $_typeOfToken = self::TOKEN_TYPE_DEFAULT;

    /**
     * Some Ricardo API Services don't need to have a registered token like
     * SystemService, ArticleService, SearchService, BrandingService
     * but they need an anonymous token
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetAnonymousTokenCredentialResult": {
     *      "TokenCredential": {
     *       "SessionDuration":0,
     *       "TokenCredentialKey":"[ANONYMOUS_TOKEN]",
     *       "TokenExpirationDate":"\/Date(3453314340000+0200)\/"
     *      }
     *     }
     *  }
     * </pre>
     * @return array
     */
    public function getAnonymousTokenCredential()
    {
        return array(
            'method' => 'GetAnonymousTokenCredential',
            'params' =>  array('getAnonymousTokenCredentialParameter' => array())
        );
    }

    /**
     * Get the result fo the API call to get the anonymous token
     *
     * Array returned:
     * <pre>
     * array(
     *     'SessionDuration',
     *     'TokenCredentialKey',
     *     'TokenExpirationDate'
     * );
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getAnonymousTokenCredentialResult(array $data)
    {
        if (isset($data['GetAnonymousTokenCredentialResult']) && isset($data['GetAnonymousTokenCredentialResult']['TokenCredential'])) {
            return $data['GetAnonymousTokenCredentialResult']['TokenCredential'];
        }

        return array();
    }

    /**
     * Ask for temporary credential for very first time use. Return a validationUrl where to redirect a user
     * to autorize the application and Temporary Key.
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "CreateTemporaryCredentialResult": {
     *       "TemporaryCredential": {
     *         "ExpirationDate": "\/Date(1385462160000+0100)\/",
     *         "TemporaryCredentialKey": "[TEMPORARY_TOKEN]",
     *         "ValidationUrl": "http://www.ch.betaqxl.com/ApiConnect/Login/Index?token=XXXXX-XXXX-XXXX-XXXX-XXXXXXXXXX&countryId=2&partnershipId=XXXX&partnerurl=http://www.myshop.com/mypage/"
     *       }
     *     }
     *   }
     * </pre>
     *
     * @return array
     */
    public function getTemporaryCredential()
    {
        return array(
            'method' => 'CreateTemporaryCredential',
            'params' => array('createTemporaryCredentialParameter' => array())
        );
    }

    /**
     * Get the result of the temporary credential.
     * Take care here, the user will have to be redirected to validate it thanks to the validationUrl variable
     *
     * Array returned:
     * <pre>
     * array(
     *     'ExpirationDate',
     *     'TemporaryCredentialKey',
     *     'ValidationUrl'
     * );
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getTemporaryCredentialResult($data)
    {
        if (isset($data['CreateTemporaryCredentialResult']) && isset($data['CreateTemporaryCredentialResult']['TemporaryCredential'])) {
            return $data['CreateTemporaryCredentialResult']['TemporaryCredential'];
        }

        return array();
    }

    /**
     * Ask for the "real" token, providing the [TEMPORARY_TOKEN] received from the method createTemporaryCredential
     * and also as a get parameter when user is returning from the validationURl.
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "CreateTokenCredentialResult": {
     *       "TokenCredential": {
     *         "SessionDuration": 30,
     *         "TokenCredentialKey": "[REAL_TOKEN]",
     *         "TokenExpirationDate": "\/Date(1386664920000+0100)\/"
     *       }
     *     }
     *   }
     * </pre>
     *
     * @param string $temporaryCredentialKey
     * @return array
     */
    public function getTokenCredential($temporaryCredentialKey)
    {
        return array(
            'method' => 'CreateTokenCredential',
            'params' => array('createTokenCredentialParameter' => array('TemporaryCredentialKey' => $temporaryCredentialKey))
        );
    }

    /**
     * Get the result of the token credential
     *
     * Array returned:
     * <pre>
     * array(
     *     'SessionDuration',
     *     'TokenCredentialKey',
     *     'TokenExpirationDate'
     * );
     * </pre>
     *
     * @param array $data
     * @return array
     */
    public function getTokenCredentialResult($data)
    {
        if (isset($data['CreateTokenCredentialResult']) && isset($data['CreateTokenCredentialResult']['TokenCredential'])) {
            return $data['CreateTokenCredentialResult']['TokenCredential'];
        }

        return array();
    }

    /**
     * After the SessionDuration timeout, the token need to be refreshed
     * You will get a new token credential in return. If TokenExpirationDate is above
     * of the current date, you will have to create again a temporary credential (sic!)
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "RefreshTokenCredentialResult": {
     *       "TokenCredential": {
     *         "SessionDuration": 30,
     *         "TokenCredentialKey": "[REAL_TOKEN]",
     *         "TokenExpirationDate": "\/Date(1386664920000+0100)\/"
     *       }
     *     }
     *   }
     * </pre>
     */
    public function getRefreshTokenCredential($tokenCredentialKey)
    {
        return array(
            'method' => 'RefreshTokenCredential',
            'params' => array('refreshTokenCredentialParameter' => array('TokenCredentialKey' => $tokenCredentialKey))
        );
    }

    /**
     * Some API methods needs an antiforgery token to prevent Man-In-The-Middle attack
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "CreateAntiforgeryTokenResult": {
     *       "TokenCredential": {
     *         "AntiforgeryTokenKey": "[REAL_TOKEN]",
     *         "TokenExpirationDate": "\/Date(1386664920000+0100)\/"
     *       }
     *     }
     *   }
     * </pre>
     */
    public function getAntiforgeryToken()
    {
        return array(
            'method' => 'CreateAntiforgeryToken',
            'params' => array('createAntiforgeryTokenParameter' => array())
        );
    }
}