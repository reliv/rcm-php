<?php

namespace RcmUser\Api\Acl;

use Interop\Container\ContainerInterface;
use RcmUser\Api\Authentication\GetIdentity;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return IsAllowedBasic
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedBasic(
            $serviceContainer->get(GetIdentity::class),
            $serviceContainer->get(IsUserAllowed::class)
        );
    }
}
