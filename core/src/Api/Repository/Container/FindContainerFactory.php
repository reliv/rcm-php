<?php

namespace Rcm\Api\Repository\Container;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainerFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindContainer
     */
    public function __invoke($serviceContainer)
    {
        return new FindContainer(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
