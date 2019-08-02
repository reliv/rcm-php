<?php

namespace Rcm\RequestContext;

use Rcm\RequestContext\RequestContextBindings;
use Reliv\ZfConfigFactories\AbstractConfigFactory;
use Zend\ServiceManager\AbstractFactoryInterface;

/**
 * This makes ZfConfigFactories work with the requst_context container/service-manager
 *
 * Class ZfConfigFactoriesRequestContextFactory
 * @package Reliv\ZfConfigFactories\ConcreteFactory
 */
class ZfConfigFactoriesRequestContextFactory extends AbstractConfigFactory implements AbstractFactoryInterface
{
    /**
     * @var string the config key of the target service manager
     */
    protected $serviceMgrKey = RequestContextBindings::REQUEST_CONTEXT_CONTAINER_CONFIG_KEY;

    /**
     * @var bool used know it we must look for the real service locator inside the given service locator
     */
    protected $serviceMgrIsRoot = true;
}
