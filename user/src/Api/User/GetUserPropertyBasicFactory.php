<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use RcmUser\User\Service\UserPropertyService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserPropertyBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return GetUserPropertyBasic
     */
    public function __invoke($serviceContainer)
    {
        return new GetUserPropertyBasic(
            $serviceContainer->get(UserPropertyService::class)
        );
    }
}
