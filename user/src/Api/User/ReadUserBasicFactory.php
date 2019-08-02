<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ReadUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return ReadUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new ReadUserBasic(
            $serviceContainer->get(ReadUserResult::class)
        );
    }
}
