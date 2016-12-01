<?php

namespace Rcm\Factory;

use Interop\Container\ContainerInterface;
use Rcm\Service\LocaleService;

/**
 * Class ServiceLocaleServiceFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ServiceLocaleServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return LocaleService
     */
    public function __invoke($container)
    {
        return new LocaleService(
            $container->get('Config')
        );
    }
}
