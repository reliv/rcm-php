<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @deprecated Use SiteSecureRepo instead
 */
class CopySiteFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return CopySite
     */
    public function __invoke($serviceContainer)
    {
        return new CopySite();
    }
}
