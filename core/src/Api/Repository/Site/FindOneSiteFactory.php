<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOneSiteFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindOneSite
     */
    public function __invoke($serviceContainer)
    {
        return new FindOneSite(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
