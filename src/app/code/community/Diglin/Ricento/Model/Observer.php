<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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
     */
    public function addAutoloader()
    {
        if (!self::$shouldAdd) {
            return;
        }

        foreach ($this->getNamespacesToRegister() as $namespace) {
            if (is_dir(Mage::getBaseDir('lib') . DS . $namespace)) {
                $args = array($namespace, Mage::getBaseDir('lib') . DS . $namespace);
                $autoloader = Mage::getModel("diglin_ricento/splAutoloader", $args);
                $autoloader->register();
            }
        }

        self::$shouldAdd = false;
    }
}