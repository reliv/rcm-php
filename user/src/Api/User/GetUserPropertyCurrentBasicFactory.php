<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use RcmUser\Api\Authentication\GetIdentity;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserPropertyCurrentBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return GetUserPropertyCurrentBasic
     */
    public function __invoke($serviceContainer)
    {
        return new GetUserPropertyCurrentBasic(
            $serviceContainer->get(GetIdentity::class),
            $serviceContainer->get(GetUserProperty::class)
        );
    }
}
