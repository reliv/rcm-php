<?php

namespace Rcm\RequestContext;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * Register this middleware early in your pipeline to init request context
 *
 * Class InitRequestContextMiddleware
 * @package Rcm\RequestContext
 */
class InitRequestContextMiddleware implements MiddlewareInterface
{

    protected $config;

    protected $container;

    public function __construct(
        array $config,
        ContainerInterface $container
    ) {
        $this->config = $config;
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $requestContextContainer = new ServiceManager(
            new Config(
                $this->config[RequestContextBindings::REQUEST_CONTEXT_CONTAINER_CONFIG_KEY]
            )
        );
        $requestContextContainer->setService(AppContext::class, $this->container);
        $requestContextContainer->setService(CurrentRequest::class, $request);
        $request = $request->withAttribute(RequestContextBindings::REQUEST_ATTRIBUTE, $requestContextContainer);
        $this->container->setService(CurrentRequestContext::class, $requestContextContainer);

        return $delegate->process($request);
    }
}
