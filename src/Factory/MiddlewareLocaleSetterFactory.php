<?php

namespace Rcm\Factory;

use Interop\Container\ContainerInterface;
use Rcm\Middleware\LocaleSetter;
use Rcm\Service\LocaleService;
use Rcm\Service\SiteService;

/**
 * Class MiddlewareLocaleSetterFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class MiddlewareLocaleSetterFactory
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
