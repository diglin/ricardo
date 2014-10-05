<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Observer
 *
 * Highly inspired from the Magento Hackathon Project: https://github.com/magento-hackathon/Magento-PSR-0-Autoloader
 * We do our own implementation to merge it in only one extension and to remove useless methods for our case
 *
 */
class Diglin_Ricento_Model_Observer
{
    const CONFIG_PATH_PSR0NAMESPACES = 'global/psr0_namespaces';

    static $shouldAdd = true;

    protected function getNamespacesToRegister()
    {
        $namespaces = array();
        $node = Mage::getConfig()->getNode(self::CONFIG_PATH_PSR0NAMESPACES);
        if ($node && is_array($node->asArray())) {
            $namespaces = array_keys($node->asArray());
        }
        return $namespaces;
    }

    /**
     * Add PSR-0 Autoloader for our Diglin_Ricardo library
     *
     * Event
     * - resource_get_tablename
     * - add_spl_autoloader
     */
    public function addAutoloader()
    {
        if (!self::$shouldAdd) {
            return;
        }

        foreach ($this->getNamespacesToRegister() as $namespace) {
            $namespace = str_replace('_', '/', $namespace);
            if (is_dir(Mage::getBaseDir('lib') . DS . $namespace)) {
                $args = array($namespace, Mage::getBaseDir('lib') . DS . $namespace);
                $autoloader = Mage::getModel("diglin_ricento/splAutoloader", $args);
                $autoloader->register();
            }
        }

        self::$shouldAdd = false;
    }

    public function reduceInventory(Varien_Event_Observer $observer)
    {
        /**
         * @todo implement logic to reduce inventory on ricardo side when inventory decrease after a non ricardo order
         */

        /**
         * 1. Is Product part of a listed products listing
         * 2. Update the quantity on ricardo side
         */
    }

    /**
     * Event
     * - adminhtml_block_html_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function disableFormField(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Customer_Edit_Tab_Account) {
            $block->getForm()->getElement('ricardo_username')->setDisabled(true);
            $block->getForm()->getElement('ricardo_customer_id')->setDisabled(true);
        }
    }
}