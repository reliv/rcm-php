<?php

namespace Rcm\Factory;

use Rcm\Service\SiteManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class SiteManagerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return SiteManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\DomainManager $domainManager */
        $domainManager = $serviceLocator->get('Rcm\Service\DomainManager');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache         = $serviceLocator->get('Rcm\Service\Cache');

        return new SiteManager(
            $domainManager,
            $entityManager,
            $cache
        );
    }
}
