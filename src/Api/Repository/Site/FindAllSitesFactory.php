<?php

namespace Rcm\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindAllSitesFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindAllSites
     */
    public function __invoke($serviceContainer)
    {
        return new FindAllSites(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
