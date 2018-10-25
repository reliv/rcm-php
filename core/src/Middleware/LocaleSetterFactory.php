<?php

namespace Rcm\Middleware;

use Interop\Container\ContainerInterface;
use Rcm\Service\LocaleService;
use Rcm\Service\SiteService;

/**
 * Class LocaleSetterFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class LocaleSetterFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return LocaleSetter
     */
    public function __invoke($container)
    {
        return new LocaleSetter(
            $container->get(SiteService::class),
            $container->get(LocaleService::class)
        );
    }
}
