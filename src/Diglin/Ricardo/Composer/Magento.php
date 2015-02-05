<?php
/**
 * Diglin GmbH - Switzerland
 *
 * This file is part of a Diglin GmbH module.
 *
 * This Diglin GmbH module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
namespace Diglin\Ricardo\Composer;

use Composer\Script\PackageEvent;

class Magento
{
    /**
     * Copy the Diglin Ricardo Library into the appropriate Magento lib folder
     *
     * @param PackageEvent $event
     */
    public static function postPackageAction(PackageEvent $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();

        if (isset($extras['magento-root-dir'])) {
            $magentoPath = $extras['magento-root-dir'];
            if (is_dir($magentoPath . 'lib')) {
                if (is_dir($magentoPath . 'lib/Diglin/Ricardo')) {
                    self::_recursiveRmDir($magentoPath . 'lib/Diglin/Ricardo');
                }
                self::_recurseCopy(dirname(__DIR__), $magentoPath . 'lib/Diglin/Ricardo');
            }
        }
    }

    /**
     * Remove the installed library Diglin Ricardo from the lib Magento folder
     *
     * @param PackageEvent $event
     */
    public static function cleanPackageAction(PackageEvent $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();

        if (isset($extras['magento-root-dir'])) {
            $magentoPath = $extras['magento-root-dir'];
            if (is_dir($magentoPath . 'lib/Diglin/Ricardo')) {
                self::_recursiveRmDir($magentoPath . 'lib/Diglin/Ricardo');
            }
        }
    }

    /**
     * Copy recursively the source to a target
     *
     * @param string $src
     * @param string $dst
     */
    protected static function _recurseCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::_recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Remove directory recursively
     *
     * @param $dir
     * @return bool
     */
    public static function _recursiveRmDir($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::_recursiveRmDir("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
