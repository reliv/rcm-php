<?php

namespace Rcm\Factory;

use Rcm\EventListener\RcmRouteListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory class for AssetManagerService
 *
 * @category   AssetManager
 * @package    AssetManager
 */
class RouteListenerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return RcmRouteListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\DomainManager $domainManager */
        $domainManager = $serviceLocator->get('rcmDomainManager');

        return new RcmRouteListener($domainManager);
    }
}
