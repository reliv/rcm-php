<?php

namespace Rcm\Factory;

use Rcm\Service\LayoutManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory class for AssetManagerService
 *
 * @category   AssetManager
 * @package    AssetManager
 */
class LayoutManagerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return LayoutManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\SiteManager $siteManager */
        $siteManager = $serviceLocator->get('rcmSiteManager');
        $config = $serviceLocator->get('config');

        return new LayoutManager(
            $siteManager,
            $config
        );
    }
}
