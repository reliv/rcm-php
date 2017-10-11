<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Api\Repository\Site\FindSite;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CopyPageFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return CopyPage
     */
    public function __invoke($serviceContainer)
    {
        return new CopyPage(
            $serviceContainer->get(EntityManager::class),
            $serviceContainer->get(FindSite::class),
            $serviceContainer->get(FindPageById::class),
            $serviceContainer->get(AssertCanCreateSitePage::class)
        );
    }
}
