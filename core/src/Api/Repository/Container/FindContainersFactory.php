<?php

namespace Rcm\Api\Repository\Container;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainersFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindContainers
     */
    public function __invoke($serviceContainer)
    {
        return new FindContainers(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
