<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CopySiteFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return CopySite
     */
    public function __invoke($serviceContainer)
    {
        return new CopySite(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
