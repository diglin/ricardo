<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Composer;

use Composer\Script\Event;
use Composer\Script\PackageEvent;

class Magento
{
    const PACKAGE_NAME = 'diglin/ricardo';

    public static function postAction(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();
        $packageName = $event->getComposer()->getPackage()->getName();


        $event->getIO()->write($packageName);
        
        if ($packageName != self::PACKAGE_NAME)
        {
            return;
        }

        if (isset($extras['magento-root-dir'])) {
            $magentoPath = $extras['magento-root-dir'];
            if (is_dir($magentoPath . 'lib')) {
                self::_recursiveRmDir($magentoPath . 'lib/Diglin/Ricardo');
                self::_recurseCopy(dirname(__DIR__), $magentoPath . 'lib/Diglin/Ricardo');
            }
        }
    }

    public static function cleanAction(PackageEvent $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();
        $packageName = $event->getComposer()->getPackage()->getName();

        if ($packageName != self::PACKAGE_NAME)
        {
            return;
        }

        if (isset($extras['magento-root-dir'])) {
            $magentoPath = $extras['magento-root-dir'];
            if (is_dir($magentoPath . 'lib/Diglin/Ricardo')) {
                self::_recursiveRmDir($magentoPath . 'lib/Diglin/Ricardo');
            }
        }
    }

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

    public static function _recursiveRmDir($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::_recursiveRmDir("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}