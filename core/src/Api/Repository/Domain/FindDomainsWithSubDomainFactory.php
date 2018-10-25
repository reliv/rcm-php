<?php

namespace Rcm\Api\Repository\Domain;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindDomainsWithSubDomainFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindDomainsWithSubDomain
     */
    public function __invoke($serviceContainer)
    {
        return new FindDomainsWithSubDomain(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
