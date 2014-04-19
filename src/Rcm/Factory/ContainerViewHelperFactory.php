<?php

namespace Rcm\Factory;

use Rcm\View\Helper\Container;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ContainerViewHelperFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $viewServiceManager Zend View Helper Mgr
     *
     * @return Container
     */
    public function createService(ServiceLocatorInterface $viewServiceManager)
    {
        /** @var \Zend\View\HelperPluginManager $viewManager */
        $viewManager = $viewServiceManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $viewManager->getServiceLocator();

        /** @var \Rcm\Service\ContainerManager $containerManager */
        $containerManager = $serviceLocator->get('Rcm\Service\ContainerManager');

        /** @var \Rcm\Service\PageManager $pageManager */
        return new Container($containerManager);
    }
}
