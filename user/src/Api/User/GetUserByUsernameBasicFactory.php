<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserByUsernameBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return GetUserByUsernameBasic
     */
    public function __invoke($serviceContainer)
    {
        return new GetUserByUsernameBasic(
            $serviceContainer->get(BuildNewUser::class),
            $serviceContainer->get(GetUser::class)
        );
    }
}
