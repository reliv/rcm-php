<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindPagesByTypeFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindPagesByType
     */
    public function __invoke($serviceContainer)
    {
        return new FindPagesByType(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
