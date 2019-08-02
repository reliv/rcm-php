<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SetIdentityInsecureFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return SetIdentityInsecure
     */
    public function __invoke($serviceContainer)
    {
        return new SetIdentityInsecure(
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
