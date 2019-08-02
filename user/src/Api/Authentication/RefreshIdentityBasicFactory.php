<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Api\User\ReadUserResult;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RefreshIdentityBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return RefreshIdentityBasic
     */
    public function __invoke($serviceContainer)
    {
        return new RefreshIdentityBasic(
            $serviceContainer->get(GetIdentity::class),
            $serviceContainer->get(ReadUserResult::class),
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
