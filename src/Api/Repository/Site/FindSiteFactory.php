<?php

namespace Rcm\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindSiteFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindSite
     */
    public function __invoke($serviceContainer)
    {
        return new FindSite(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
