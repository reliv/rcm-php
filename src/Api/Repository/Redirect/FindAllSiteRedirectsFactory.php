<?php

namespace Rcm\Api\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindAllSiteRedirectsFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindAllSiteRedirects
     */
    public function __invoke($serviceContainer)
    {
        return new FindAllSiteRedirects(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
