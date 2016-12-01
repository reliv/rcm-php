<?php

namespace Rcm\Factory;

use Interop\Container\ContainerInterface;
use Rcm\Service\DomainService;

/**
 * Class ServiceDomainServiceFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ServiceDomainServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DomainService
     */
    public function __invoke($container)
    {
        return new DomainService(
            $container->get('Config')
        );
    }
}
