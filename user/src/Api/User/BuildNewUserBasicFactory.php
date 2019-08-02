<?php

namespace RcmUser\Api\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildNewUserBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return BuildNewUserBasic
     */
    public function __invoke($serviceContainer)
    {
        return new BuildNewUserBasic(
            $serviceContainer->get(BuildUser::class)
        );
    }
}
