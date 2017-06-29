<?php

namespace Rcm\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Service\DomainService;
use Rcm\Service\SiteService;
use RcmUser\Service\RcmUserService;

/**
 * Class ServiceCurrentSiteFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ServiceSiteServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return SiteService
     */
    public function __invoke($container)
    {
        return new SiteService(
            $container->get(DomainService::class),
            $container->get(EntityManager::class)
        );
    }
}
