<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOnePageFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindOnePage
     */
    public function __invoke($serviceContainer)
    {
        return new FindOnePage(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
