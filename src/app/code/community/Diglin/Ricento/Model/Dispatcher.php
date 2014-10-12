<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Dispatcher
 */
class Diglin_Ricento_Model_Dispatcher
{
    /**
     * @var array
     */
    protected $_adapterClass = array();

    /**
     * Get the dispatcher for a specific type
     *
     * @param string $type
     * @return Diglin_Ricento_Model_Dispatcher_Abstract | boolean
     * @throws Mage_Core_Exception
     */
    public function dispatch ($type)
    {
        $className = $this->getAdapterClassName($type);

        if (empty($className) || !class_exists($className)) {
            Mage::throwException(Mage::helper('diglin_ricento')->__("The dispatcher of type '%s' is not found at %s.", $type, Diglin_Ricento_Helper_Data::NODE_DISPATCHER_TYPES));
        }

        $adapter = new $className();

        if (!($adapter instanceof Diglin_Ricento_Model_Dispatcher_Abstract)) {
            Mage::throwException(Mage::helper('diglin_ricento')->__("Dispatcher Class %s doesn't implements Diglin_Ricento_Model_Dispatcher_Abstract.", $className));
        }

        return $adapter;
    }

    /**
     * Get adapter class name from config
     *
     * @param string $type
     * @return string
     */
    public function getAdapterClassName ($type)
    {
        if (isset($this->_adapterClass[$type])) {
            return $this->_adapterClass[$type];
        }

        return $this->_adapterClass[$type] = (string) Mage::getConfig()->getNode(Diglin_Ricento_Helper_Data::NODE_DISPATCHER_TYPES . '/' . $type)->class;
    }
}