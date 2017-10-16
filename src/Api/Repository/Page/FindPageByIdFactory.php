<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindPageByIdFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindPageById
     */
    public function __invoke($serviceContainer)
    {
        return new FindPageById(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
