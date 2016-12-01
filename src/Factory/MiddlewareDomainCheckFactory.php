<?php

namespace Rcm\Factory;

use Interop\Container\ContainerInterface;
use Rcm\Middleware\DomainCheck;
use Rcm\Service\DomainRedirectService;
use Rcm\Service\SiteService;

/**
 * Class MiddlewareDomainCheckFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class MiddlewareDomainCheckFactory
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
