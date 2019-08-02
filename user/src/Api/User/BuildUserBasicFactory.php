<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use RcmUser\User\Service\UserDataService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return BuildUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new BuildUserBasic(
            $serviceContainer->get(UserDataService::class)
        );
    }
}
