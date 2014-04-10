<?php

namespace Rcm\Factory;

use Rcm\Service\SiteManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory class for AssetManagerService
 *
 * @category   AssetManager
 * @package    AssetManager
 */
class SiteManagerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return SiteManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\DomainManager $domainManager */
        $domainManager = $serviceLocator->get('rcmDomainManager');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('em');

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache         = $serviceLocator->get('rcmCache');

        return new SiteManager(
            $domainManager,
            $entityManager,
            $cache
        );
    }
}
