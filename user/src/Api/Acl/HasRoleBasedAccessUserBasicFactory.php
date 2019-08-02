<?php

namespace RcmUser\Api\Acl;

use Interop\Container\ContainerInterface;
use RcmUser\Acl\Service\AuthorizeService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HasRoleBasedAccessUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return HasRoleBasedAccessUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new HasRoleBasedAccessUserBasic(
            $serviceContainer->get(AuthorizeService::class)
        );
    }
}
