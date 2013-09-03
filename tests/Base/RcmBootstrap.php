<?php

namespace Rcm\Tests\Base;

use Zend\Config\Config;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;


//Load Base Test Cases
require __DIR__.'/DoctrineTestCase.php';

/**
 * Test bootstrap, for setting up autoloading
 */
class RcmBootstrap
{
    protected static $serviceManager;

    public static function init(Array $moduleConfig)
    {
        $mainConfig = include __DIR__.'/application.test.config.php';

        chdir(__DIR__.'/../../../../../');
        static::initAutoloader();

        $config = new Config($mainConfig);
        $moduleZendConfig = new Config($moduleConfig);
        $config->merge($moduleZendConfig);

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        static::$serviceManager = $serviceManager;
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    protected static function initAutoloader()
    {
        include 'init_autoloader.php';
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) return false;
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

