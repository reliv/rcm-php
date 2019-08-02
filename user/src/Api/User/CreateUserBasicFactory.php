<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return CreateUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new CreateUserBasic(
            $serviceContainer->get(CreateUserResult::class)
        );
    }
}
