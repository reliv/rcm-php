<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindSitesFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindSites
     */
    public function __invoke($serviceContainer)
    {
        return new FindSites(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
