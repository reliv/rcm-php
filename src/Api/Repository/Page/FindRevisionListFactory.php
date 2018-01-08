<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindRevisionListFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindRevisionList
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        return new FindRevisionList(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
