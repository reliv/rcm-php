<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use RcmUser\User\Service\UserDataService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpdateUserResultBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return UpdateUserResultBasic
     */
    public function __invoke($serviceContainer)
    {
        return new UpdateUserResultBasic(
            $serviceContainer->get(UserDataService::class)
        );
    }
}
