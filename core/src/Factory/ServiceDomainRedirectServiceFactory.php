<?php

namespace Rcm\Factory;

use Interop\Container\ContainerInterface;
use Rcm\Service\DomainRedirectService;
use Rcm\Service\DomainService;

/**
 * Class ServiceDomainRedirectServiceFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ServiceDomainRedirectServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DomainRedirectService
     */
    public function __invoke($container)
    {
        return new DomainRedirectService(
            $container->get(DomainService::class)
        );
    }
}
