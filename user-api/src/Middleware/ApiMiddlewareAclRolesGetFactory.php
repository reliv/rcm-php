<?php

namespace RcmUser\Api\Middleware;

use Psr\Container\ContainerInterface;
use RcmUser\Acl\Service\AclDataService;
use RcmUser\Api\MiddlewareResponse\GetExceptionResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiMiddlewareAclRolesGetFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return ApiMiddlewareAclRolesGet
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        return new ApiMiddlewareAclRolesGet(
            $serviceContainer->get(AclDataService::class),
            $serviceContainer->get(GetExceptionResponse::class)
        );
    }
}
