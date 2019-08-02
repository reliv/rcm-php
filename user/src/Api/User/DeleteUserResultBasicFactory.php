<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use RcmUser\User\Service\UserDataService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DeleteUserResultBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return DeleteUserResultBasic
     */
    public function __invoke($serviceContainer)
    {
        return new DeleteUserResultBasic(
            $serviceContainer->get(UserDataService::class)
        );
    }
}
