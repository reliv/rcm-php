<?php

namespace Rcm\Middleware;

use Interop\Container\ContainerInterface;
use Rcm\Service\RedirectService;

/**
 * Class RedirectCheckFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class RedirectCheckFactory
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
