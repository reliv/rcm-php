<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SavePageFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return SavePage
     */
    public function __invoke($serviceContainer)
    {
        return new SavePage(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
