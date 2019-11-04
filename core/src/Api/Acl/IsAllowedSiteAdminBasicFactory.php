<?php

namespace Rcm\Api\Acl;

use Interop\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use Rcm\RequestContext\RequestContext;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedSiteAdminBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedSiteAdminBasic
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedSiteAdminBasic(
            $serviceContainer->get(RequestContext::class)
        );
    }
}
