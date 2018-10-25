<?php

namespace Rcm\Api\Repository\Page;

use Interop\Container\ContainerInterface;
use Rcm\Api\Repository\Site\FindSite;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertCanCreateSitePageFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return AssertCanCreateSitePage
     */
    public function __invoke($serviceContainer)
    {
        return new AssertCanCreateSitePage(
            $serviceContainer->get(AllowDuplicateForPageType::class),
            $serviceContainer->get(FindSite::class),
            $serviceContainer->get(PageExists::class)
        );
    }
}
