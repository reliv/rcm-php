<?php

namespace Rcm\Repository\Page;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PageExistsFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return PageExists
     */
    public function __invoke($serviceContainer)
    {
        return new PageExists(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
