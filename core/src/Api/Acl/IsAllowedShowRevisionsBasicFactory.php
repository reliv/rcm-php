<?php

namespace Rcm\Api\Acl;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use Rcm\RequestContext\RequestContext;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedShowRevisionsBasicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedShowRevisionsBasic
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedShowRevisionsBasic(
            $serviceContainer->get(EntityManager::class),
            $serviceContainer->get(RequestContext::class)
        );
    }
}
