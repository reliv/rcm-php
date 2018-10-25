<?php

namespace Rcm\Api\Acl;

use Interop\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use RcmUser\Api\Acl\IsAllowed;

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
            $serviceContainer->get(IsAllowed::class)
        );
    }
}
