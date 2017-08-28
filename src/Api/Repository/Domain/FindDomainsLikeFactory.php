<?php

namespace Rcm\Api\Repository\Domain;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindDomainsLikeFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindDomainsLike
     */
    public function __invoke($serviceContainer)
    {
        return new FindDomainsLike(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
