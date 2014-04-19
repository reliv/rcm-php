<?php

namespace Rcm\Factory;

use Rcm\Service\ContainerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ContainerManagerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return ContainerManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\SiteManager $siteManager */
        $siteManager   = $serviceLocator->get('Rcm\Service\SiteManager');

        /** @var \Rcm\Service\PluginManager $pluginManager */
        $pluginManager = $serviceLocator->get('Rcm\Service\PluginManager');

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Zend\Cache\Storage\StorageInterface $rcmCache */
        $rcmCache      = $serviceLocator->get('Rcm\Service\Cache');

        return new ContainerManager(
            $siteManager,
            $pluginManager,
            $entityManager,
            $rcmCache
        );
    }
}
