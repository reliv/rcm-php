<?php

namespace Rcm\Middleware;

use Interop\Container\ContainerInterface;
use Rcm\Api\GetSiteIdByRequest;

class SiteExistsCheckFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return SiteExistsCheck
     */
    public function __invoke($container)
    {
        return new SiteExistsCheck(
            $container->get(GetSiteIdByRequest::class),
            $container->get('config')['Rcm']['siteExistsCheckIgnoredUrls']
        );
    }
}
