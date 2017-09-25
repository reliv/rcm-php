<?php

namespace Rcm\Middleware;

use Interop\Container\ContainerInterface;
use Rcm\Api\GetSiteIdByRequest;

/**
 * @author James Jervis - https://github.com/jerv13
 */
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
            $container->get(GetSiteIdByRequest::class)
        );
    }
}
