<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use RcmUser\User\Service\UserDataService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateUserResultBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return CreateUserResultBasic
     */
    public function __invoke($serviceContainer)
    {
        return new CreateUserResultBasic(
            $serviceContainer->get(UserDataService::class)
        );
    }
}
