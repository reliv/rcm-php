<?php

namespace Rcm\Api\Acl;

use Interop\Container\ContainerInterface;
use RcmUser\Api\Acl\HasRoleBasedAccess;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HasRoleBasedAccessBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return HasRoleBasedAccessBasic
     */
    public function __invoke($serviceContainer)
    {
        return new HasRoleBasedAccessBasic(
            $serviceContainer->get(HasRoleBasedAccess::class)
        );
    }
}
