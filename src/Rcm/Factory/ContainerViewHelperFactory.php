<?php

namespace Rcm\Factory;

use Rcm\View\Helper\Container;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory class for AssetManagerService
 *
 * @category   AssetManager
 * @package    AssetManager
 */
class ContainerViewHelperFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $viewServiceManager
     * @return Container
     */
    public function createService(ServiceLocatorInterface $viewServiceManager)
    {

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $viewServiceManager->getServiceLocator();

        /** @var \Rcm\Service\ContainerManager $containerManager */
        $containerManager = $serviceLocator->get('rcmContainerManager');

        /** @var \Rcm\Service\PageManager $pageManager */
        return new Container($containerManager);
    }
}
