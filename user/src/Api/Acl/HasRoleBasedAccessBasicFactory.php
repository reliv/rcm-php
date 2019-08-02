<?php

namespace RcmUser\Api\Acl;

use Interop\Container\ContainerInterface;
use RcmUser\Api\Authentication\GetIdentity;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HasRoleBasedAccessBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return HasRoleBasedAccessBasic
     */
    public function __invoke($serviceContainer)
    {
        return new HasRoleBasedAccessBasic(
            $serviceContainer->get(GetIdentity::class),
            $serviceContainer->get(HasRoleBasedAccessUser::class)
        );
    }
}
