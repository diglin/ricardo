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
     * @var Security
     */
    protected $_securityServiceModel = array();

    /**
     * @param int|Mage_Core_Model_Website $website
     * @return Security
     */
    public function getSecurityServiceModel($website = 0)
    {
        if (!is_numeric($website) && !($website instanceof Mage_Core_Model_Website)) {
            Mage::throwException(Mage::helper('diglin_ricento')->__('Website ID is not an integer'));
        } elseif ($website instanceof Mage_Core_Model_Website) {
            $websiteId = $website->getId();
        } else {
            $websiteId = $website;
        }

        if (!isset($this->_securityServiceModel[$websiteId])) {
            $object = $this->getServiceManager($website)->getSecurityManager();
            $this->_securityServiceModel[$websiteId] = Mage::objects()->save($object);
        }
        return Mage::objects()->load($this->_securityServiceModel[$websiteId]);
    }

    /**
     * Get the validation Url necessary if simulation of authorization process is not allowed
     *
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    public function getValidationUrl(Mage_Core_Model_Store $store)
    {
        $websiteId = $store->getWebsiteId();
        $validationUrl = $this->getSecurityServiceModel($websiteId)->getValidationUrl();

        // Refresh the database cause of new data after getting validation url
        $apiToken = Mage::getModel('diglin_ricento/api_token')->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY, $websiteId);
        $apiToken
            ->setWebsiteId($websiteId)
            ->setToken($this->getSecurityServiceModel()->getTemporaryToken())
            ->setExpirationDate($this->getSecurityServiceModel()->getTemporaryTokenExpirationDate())
            ->setTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY)
            ->save();

        return $validationUrl;
    }
}