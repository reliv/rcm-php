<?php

namespace Rcm\Middleware;

use Interop\Container\ContainerInterface;
use Rcm\Service\DomainRedirectService;
use Rcm\Service\SiteService;

/**
 * Class DomainCheckFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class DomainCheckFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DomainCheck
     */
    public function __invoke($container)
    {
        return new DomainCheck(
            $container->get(SiteService::class),
            $container->get(DomainRedirectService::class)
        );
    }
}
