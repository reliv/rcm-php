<?php

namespace Rcm\Factory;

use Rcm\EventListener\DispatchListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class DispatchListenerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return DispatchListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager     = $serviceLocator->get('Rcm\\Service\\LayoutManager');

        /** @var \Rcm\Service\SiteManager $siteManager */
        $siteManager       = $serviceLocator->get('Rcm\\Service\\SiteManager');

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('viewHelperManager');

        return new DispatchListener($layoutManager, $siteManager, $viewHelperManager);
    }
}
