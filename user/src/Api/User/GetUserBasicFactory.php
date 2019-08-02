<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return GetUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new GetUserBasic(
            $serviceContainer->get(ReadUserResult::class)
        );
    }
}
