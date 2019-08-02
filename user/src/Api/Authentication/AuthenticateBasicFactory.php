<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AuthenticateBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return AuthenticateBasic
     */
    public function __invoke($serviceContainer)
    {
        return new AuthenticateBasic(
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
