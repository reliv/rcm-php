<?php

namespace Rcm\Factory;

use Rcm\EventListener\RouteListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class RouteListenerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return RouteListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\DomainManager $domainManager */
        $domainManager = $serviceLocator->get('Rcm\Service\DomainManager');

        return new RouteListener($domainManager);
    }
}
