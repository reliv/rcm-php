<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpdateUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return UpdateUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new UpdateUserBasic(
            $serviceContainer->get(UpdateUserResult::class)
        );
    }
}
