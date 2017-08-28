<?php

namespace Rcm\Api\Repository\Domain;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindDomainByNameFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindDomainByName
     */
    public function __invoke($serviceContainer)
    {
        return new FindDomainByName(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
