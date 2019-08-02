<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DeleteUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return DeleteUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new DeleteUserBasic(
            $serviceContainer->get(DeleteUserResult::class)
        );
    }
}
