<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use RcmUser\User\Service\UserDataService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ReadUserResultBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return ReadUserResultBasic
     */
    public function __invoke($serviceContainer)
    {
        return new ReadUserResultBasic(
            $serviceContainer->get(UserDataService::class)
        );
    }
}
