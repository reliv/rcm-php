<?php

namespace Rcm\Factory;

use Zend\Cache\StorageFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Cache\ZendStorageCache;


class CacheFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return ZendStorageCache
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        return StorageFactory::factory(
            array(
                'adapter' => array(
                    'name' => $config['rcmCache']['adapter'],
                    'options' => $config['rcmCache']['options'],
                ),
                'plugins' => $config['rcmCache']['plugins'],
            )
        );
    }
}
