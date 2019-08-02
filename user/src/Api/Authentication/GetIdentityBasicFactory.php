<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetIdentityBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return GetIdentityBasic
     */
    public function __invoke($serviceContainer)
    {
        return new GetIdentityBasic(
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
