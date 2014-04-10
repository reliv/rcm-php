<?php

namespace Rcm\Factory;

use Rcm\EventListener\RcmDispatchListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory class for AssetManagerService
 *
 * @category   AssetManager
 * @package    AssetManager
 */
class DispatchListenerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return RcmDispatchListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager     = $serviceLocator->get('rcmLayoutManager');

        /** @var \Rcm\Service\SiteManager $siteManager */
        $siteManager       = $serviceLocator->get('rcmSiteManager');

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('viewHelperManager');

        return new RcmDispatchListener($layoutManager, $siteManager, $viewHelperManager);
    }
}
