<?php
/**
 * This file is part of Diglin_Ricento for Magento.
 *
 * @license proprietary
 * @author Fabian Schmengler <fs@integer-net.de> <fschmengler>
 * @category Diglin
 * @package Diglin_Ricento
 * @copyright Copyright (c) 2014 Diglin GmbH (http://www.diglin.com/)
 */

/**
 * Resource Model of Products_Listing
 * @package Diglin_Ricento
 */
class Diglin_Ricento_Model_Resource_Products_Listing extends Mage_Core_Model_Resource_Db_Abstract
{

// Diglin GmbH Tag NEW_CONST

// Diglin GmbH Tag NEW_VAR

    /**
     * "Aufbauart" option array
     *
     * @var string[]
     */
    protected $_statusOptions;

    /**
     * Products_Listing Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/products_listing', 'entity_id');
    }

// Diglin GmbH Tag NEW_METHOD

    /**
     * Returns option array with all possible values for "status" (key=value)
     *
     * @return string[]
     */
    public function getStatusOptions()
    {
        if ($this->_statusOptions === null) {
            $adapter = $this->_getReadAdapter();
            $select = $adapter->select()
                ->from($this->getTable('diglin_ricento/products_listing'), 'status')
                ->group('status');
            $options = $adapter->fetchCol($select);
            $this->_statusOptions = array_combine($options, $options);
            unset($this->_statusOptions['']); // empty values cannot be filtered (empty means "no filter" in grid)
        }
        return $this->_statusOptions;
    }
}