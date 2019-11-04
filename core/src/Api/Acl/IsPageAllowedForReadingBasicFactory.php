<?php

namespace Rcm\Api\Acl;

use Interop\Container\ContainerInterface;
use Rcm\Acl\GetGroupNamesByUserInterface;
use Rcm\Acl\ResourceName;
use Rcm\RequestContext\RequestContext;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\GetPsrRequest;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsPageAllowedForReadingBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsPageAllowedForReadingBasic
     */
    public function __invoke($serviceContainer)
    {
        return new IsPageAllowedForReadingBasic(
            $serviceContainer->get(ResourceName::class),
            $serviceContainer->get(GetGroupNamesByUserInterface::class),
            $serviceContainer->get(GetIdentity::class),
            $serviceContainer->get(RequestContext::class)
        );
    }
}
