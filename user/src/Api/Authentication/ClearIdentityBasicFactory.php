<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ClearIdentityBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return ClearIdentityBasic
     */
    public function __invoke($serviceContainer)
    {
        return new ClearIdentityBasic(
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
