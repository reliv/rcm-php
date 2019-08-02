<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SetIdentityBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return SetIdentityBasic
     */
    public function __invoke($serviceContainer)
    {
        return new SetIdentityBasic(
            $serviceContainer->get(GetIdentity::class),
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
