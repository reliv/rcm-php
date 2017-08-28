<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindPagesFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindPages
     */
    public function __invoke($serviceContainer)
    {
        return new FindPages(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
