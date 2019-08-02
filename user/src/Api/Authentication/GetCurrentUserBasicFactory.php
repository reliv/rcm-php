<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetCurrentUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return GetCurrentUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new GetCurrentUserBasic(
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
