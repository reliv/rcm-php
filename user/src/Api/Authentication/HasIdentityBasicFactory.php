<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HasIdentityBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return HasIdentityBasic
     */
    public function __invoke($serviceContainer)
    {
        return new HasIdentityBasic(
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
