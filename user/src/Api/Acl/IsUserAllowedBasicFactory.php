<?php

namespace RcmUser\Api\Acl;

use Interop\Container\ContainerInterface;
use RcmUser\Acl\Service\AuthorizeService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsUserAllowedBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return IsUserAllowedBasic
     */
    public function __invoke($serviceContainer)
    {
        return new IsUserAllowedBasic(
            $serviceContainer->get(AuthorizeService::class)
        );
    }
}
