<?php

namespace Rcm\Api\Acl;

use Interop\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use RcmUser\Acl\Service\AclDataService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsPageRestrictedBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsPageRestrictedBasic
     */
    public function __invoke($serviceContainer)
    {
        return new IsPageRestrictedBasic(
            $serviceContainer->get(ResourceName::class),
            $serviceContainer->get(AclDataService::class)
        );
    }
}
