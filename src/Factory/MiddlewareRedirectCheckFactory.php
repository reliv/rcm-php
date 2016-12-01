<?php

namespace Rcm\Factory;

use Interop\Container\ContainerInterface;
use Rcm\Middleware\RedirectCheck;
use Rcm\Service\RedirectService;

/**
 * Class MiddlewareRedirectCheckFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class MiddlewareRedirectCheckFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RedirectCheck
     */
    public function __invoke($container)
    {
        return new RedirectCheck(
            $container->get(RedirectService::class)
        );
    }
}
