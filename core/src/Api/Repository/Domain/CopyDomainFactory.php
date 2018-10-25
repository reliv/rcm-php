<?php

namespace Rcm\Api\Repository\Domain;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CopyDomainFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return CopyDomain
     */
    public function __invoke($serviceContainer)
    {
        return new CopyDomain(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
