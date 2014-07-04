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
 * Resource Model of Sales_Options
 * @package Diglin_Ricento
 */
class Diglin_Ricento_Model_Resource_Sales_Options extends Mage_Core_Model_Resource_Db_Abstract
{

// Diglin GmbH Tag NEW_CONST

// Diglin GmbH Tag NEW_VAR

    /**
     * Sales_Options Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/sales_options', 'options_id');
    }

// Diglin GmbH Tag NEW_METHOD

}