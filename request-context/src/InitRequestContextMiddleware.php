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

    protected $appContextContainer;

    public function __construct(
        array $config,
        ContainerInterface $appContextContainer
    ) {
        $this->config = $config;
        $this->appContextContainer = $appContextContainer;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $requestContextContainer = new ServiceManager(
            new Config(
                $this->config[RequestContextBindings::REQUEST_CONTEXT_CONTAINER_CONFIG_KEY]
            )
        );

        //Allow calls to requesetContext for services that are only in appContext to work
        $requestContextContainer->addAbstractFactory(
            new PsrContainerToZendAbstractFactory($this->appContextContainer)
        );

        //Add the current request as a service to requestContext incase anyone needs it
        $requestContextContainer->setService(CurrentRequest::class, $request);

        $request = $request->withAttribute(
            RequestContextBindings::REQUEST_ATTRIBUTE,
            $requestContextContainer
        );

        //Put the requestContext in the appContext. This is ONLY for BC support for non-PSR-middleware code.
        $this->appContextContainer->setService(
            RequestContext::class,
            $requestContextContainer
        );

        return $delegate->process($request);
    }
}
