<?php

namespace Rcm\Factory;

use Rcm\Controller\IndexController;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class IndexControllerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $controllerManager
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        /** @var \Rcm\Service\PageManager $pageManager */
        $pageManager = $serviceLocator->get('Rcm\\Service\\PageManager');

        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager = $serviceLocator->get('Rcm\\Service\\LayoutManager');

        return new IndexController(
            $pageManager,
            $layoutManager
        );
    }
}
