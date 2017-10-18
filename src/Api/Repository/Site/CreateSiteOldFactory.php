<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateSiteOldFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return CreateSiteOld
     */
    public function __invoke($serviceContainer)
    {
        return new CreateSiteOld(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
