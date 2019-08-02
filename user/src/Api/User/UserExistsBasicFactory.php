<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UserExistsBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return UserExistsBasic
     */
    public function __invoke($serviceContainer)
    {
        return new UserExistsBasic(
            $serviceContainer->get(ReadUserResult::class)
        );
    }
}
