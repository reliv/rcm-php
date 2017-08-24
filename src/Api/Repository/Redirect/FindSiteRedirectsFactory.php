<?php

namespace Rcm\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindSiteRedirectsFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindSiteRedirects
     */
    public function __invoke($serviceContainer)
    {
        return new FindSiteRedirects(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
