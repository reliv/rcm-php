<?php

namespace Rcm\Api;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetSiteByRequestFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetSiteByRequest
     */
    public function __invoke($serviceContainer)
    {
        return new GetSiteByRequest(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
