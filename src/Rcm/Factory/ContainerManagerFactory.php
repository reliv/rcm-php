<?php

namespace Rcm\Factory;

use Rcm\Service\ContainerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory class for AssetManagerService
 *
 * @category   AssetManager
 * @package    AssetManager
 */
class ContainerManagerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return ContainerManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\SiteManager $siteManager */
        $siteManager   = $serviceLocator->get('rcmSiteManager');

        /** @var \Rcm\Service\PluginManager $pluginManager */
        $pluginManager = $serviceLocator->get('rcmPluginManager');

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('em');

        /** @var \Zend\Cache\Storage\StorageInterface $rcmCache */
        $rcmCache      = $serviceLocator->get('rcmCache');

        return new ContainerManager(
            $siteManager,
            $pluginManager,
            $entityManager,
            $rcmCache
        );
    }
}
