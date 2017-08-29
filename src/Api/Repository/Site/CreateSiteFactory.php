<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateSiteFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return CreateSite
     */
    public function __invoke($serviceContainer)
    {
        return new CreateSite(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
