<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class SetDomainNameFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return SetDomainName
     */
    public function __invoke($serviceContainer)
    {
        return new SetDomainName(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
