<?php

namespace RcmUser\Api\MiddlewareResponse;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetNotAllowedResponseFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetNotAllowedResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        return new GetNotAllowedResponse();
    }
}
