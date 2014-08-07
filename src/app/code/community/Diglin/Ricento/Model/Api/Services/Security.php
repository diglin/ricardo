<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

use \Diglin\Ricardo\Managers\Security;
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
     * @param int|Mage_Core_Model_Website $website
     * @return Security
     */
    public function getServiceModel($website = 0)
    {
        $websiteId = Mage::app()->getWebsite($website)->getId();
        $key = $this->_serviceName . $websiteId;

        if (!Mage::registry($key)) {
            Mage::register($key, $this->getServiceManager($websiteId)->getSecurityManager());
        }

        return Mage::registry($key);
    }

    /**
     * Get the validation Url necessary if simulation of authorization process is not allowed
     *
     * @param Mage_Core_Model_Website $website
     * @return string
     */
    public function getValidationUrl($website)
    {
        $websiteId = Mage::app()->getWebsite($website)->getId();
        $validationUrl = $this->getServiceModel($websiteId)->getValidationUrl();

        // Refresh the database cause of new data after getting validation url
        $apiToken = Mage::getModel('diglin_ricento/api_token')->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY, $websiteId);
        $apiToken
            ->setWebsiteId($websiteId)
            ->setToken($this->getServiceModel()->getTemporaryToken())
            ->setExpirationDate($this->getServiceModel()->getTemporaryTokenExpirationDate())
            ->setTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY)
            ->save();

        return $validationUrl;
    }
}