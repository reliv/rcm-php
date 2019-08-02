<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserByIdBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return GetUserByIdBasic
     */
    public function __invoke($serviceContainer)
    {
        return new GetUserByIdBasic(
            $serviceContainer->get(BuildNewUser::class),
            $serviceContainer->get(GetUser::class)
        );
    }
}
