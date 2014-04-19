<?php

namespace Rcm\Factory;

use Rcm\Controller\IndexController;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class IndexControllerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $controllerManager Zend Controler Manager
     *
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $controllerMgr  For IDE */
        $controllerMgr = $controllerManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerMgr->getServiceLocator();

        /** @var \Rcm\Service\PageManager $pageManager */
        $pageManager = $serviceLocator->get('Rcm\Service\PageManager');

        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager = $serviceLocator->get('Rcm\Service\LayoutManager');

        return new IndexController(
            $pageManager,
            $layoutManager
        );
    }
}
