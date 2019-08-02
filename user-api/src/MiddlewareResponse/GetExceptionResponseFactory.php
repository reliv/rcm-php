<?php

namespace RcmUser\Api\MiddlewareResponse;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetExceptionResponseFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetExceptionResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        return new GetExceptionResponse();
    }
}
