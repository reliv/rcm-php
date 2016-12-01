<?php

namespace Rcm\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Service\RedirectService;
use Rcm\Service\SiteService;

/**
 * Class ServiceRedirectServiceFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ServiceRedirectServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RedirectService
     */
    public function __invoke($container)
    {
        return new RedirectService(
            $container->get(EntityManager::class),
            $container->get(SiteService::class)
        );
    }
}
