<?php

namespace Rcm\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Cache\ZendStorageCache;

/**
 * Factory class for AssetManagerService
 *
 * @category   AssetManager
 * @package    AssetManager
 */
class DoctrineCacheFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return ZendStorageCache
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Cache\Storage\StorageInterface $zendCache */
        $zendCache = $serviceLocator->get('rcmCache');

        return new ZendStorageCache($zendCache);
    }
}
