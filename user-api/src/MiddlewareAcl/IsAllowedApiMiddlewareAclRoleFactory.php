<?php

namespace RcmUser\Api\MiddlewareAcl;

use Psr\Container\ContainerInterface;
use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\MiddlewareResponse\GetNotAllowedResponse;
use RcmUser\Provider\RcmUserAclResourceProvider;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedApiMiddlewareAclRoleFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedApiMiddlewareAclRole
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        return new IsAllowedApiMiddlewareAclRole(
            $serviceContainer->get(IsAllowed::class),
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'read',
            $serviceContainer->get(GetNotAllowedResponse::class),
            IsAllowedApiMiddlewareAclRole::DEFAULT_NOT_ALLOWED_STATUS
        );
    }
}
