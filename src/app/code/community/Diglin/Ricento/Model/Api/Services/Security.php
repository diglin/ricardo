<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

use \Diglin\Ricardo\Services\ServiceAbstract;

/**
 * Class Diglin_Ricento_Model_Api_Services_Security
 */
class Diglin_Ricento_Model_Api_Services_Security extends Diglin_Ricento_Model_Api_Services_Abstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'Security';

    /**
     * Overwritten because the Service Manager has already instanciated the Security Manager model
     *
     * Be aware that using directly this method to use the methods of the object instead of using
     * the magic methods of the abstract (__call, __get, __set) will prevent to use the cache of Magento
     *
     * @return \Diglin\Ricardo\Managers\Security
     */
    public function getServiceModel()
    {
        $key = $this->_serviceName . $this->getCurrentWebsite()->getId();

        if (!Mage::registry($key)) {
            Mage::register($key, $this->getServiceManager()->getSecurityManager());
        }

        return Mage::registry($key);
    }

    /**
     * Get the validation Url necessary if simulation of authorization process is not allowed
     *
     * @return string
     */
    public function getValidationUrl()
    {
        $websiteId = $this->getCurrentWebsite()->getId();
        $serviceModel = $this->getServiceModel();
        $validationUrl = $serviceModel->getValidationUrl();

        // Refresh the database cause of new data after getting validation url
        $apiToken = Mage::getModel('diglin_ricento/api_token')->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY, $websiteId);
        $apiToken
            ->setWebsiteId($websiteId)
            ->setToken($serviceModel->getTemporaryToken())
            ->setExpirationDate($serviceModel->getTemporaryTokenExpirationDate())
            ->setTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY)
            ->save();

        return $validationUrl;
    }
}