<?php

namespace Rcm\Factory;

use Rcm\Service\DomainManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class DomainManagerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return DomainManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache         = $serviceLocator->get('Rcm\Service\Cache');

        return new DomainManager(
            $entityManager,
            $cache
        );

    }
}
