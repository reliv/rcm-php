<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindActiveSitesFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindActiveSites
     */
    public function __invoke($serviceContainer)
    {
        return new FindActiveSites(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
