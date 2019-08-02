<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsIdentityBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return IsIdentityBasic
     */
    public function __invoke($serviceContainer)
    {
        return new IsIdentityBasic(
            $serviceContainer->get(GetIdentity::class)
        );
    }
}
