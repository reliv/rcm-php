<?php

use Zend\ServiceManager\ServiceManager,
Zend\Mvc\Service\ServiceManagerConfig;

chdir(dirname(__DIR__).'/../../../');

// Composer autoloading
if (!include_once 'vendor/autoload.php') {
    throw new RuntimeException(
        'vendor/autoload.php could not be found. '
            . 'Did you run `php composer.phar install`?'
    );
}

// Get application stack configuration
$config = include 'config/application.config.php';

// Setup service manager
$serviceManager = new ServiceManager(
    new ServiceManagerConfig($config)
);

$serviceManager->setService('ApplicationConfig', $config);
$serviceManager->get('ModuleManager')->loadModules();

// Run application
$serviceManager->get('Application')->bootstrap();
