<?php

namespace Rcm\Factory;

use Rcm\Service\DomainManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory class for AssetManagerService
 *
 * @category   AssetManager
 * @package    AssetManager
 */
class DomainManagerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return DomainManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('em');

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache         = $serviceLocator->get('rcmCache');

        return new DomainManager(
            $entityManager,
            $cache
        );

    }
}
