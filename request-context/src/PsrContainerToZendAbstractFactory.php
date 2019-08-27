<?php

namespace Rcm\RequestContext;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PsrContainerToZendAbstractFactory implements AbstractFactoryInterface
{
    protected $container;

    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->container->has($requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->container->get($requestedName);
    }
}
