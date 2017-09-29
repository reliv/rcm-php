<?php

namespace Rcm\Api\Acl;

use Interop\Container\ContainerInterface;
use RcmUser\Api\Authentication\HasIdentity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsUserLoggedInBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsUserLoggedInBasic
     */
    public function __invoke($serviceContainer)
    {
        return new IsUserLoggedInBasic(
            $serviceContainer->get(HasIdentity::class)
        );
    }
}
