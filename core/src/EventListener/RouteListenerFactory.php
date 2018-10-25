<?php

namespace Rcm\EventListener;

use Interop\Container\ContainerInterface;
use Rcm\EventListener\RouteListener;
use Rcm\Service\DomainRedirectService;
use Rcm\Service\LocaleService;
use Rcm\Service\RedirectService;
use Rcm\Service\SiteService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Route Listener
 *
 * Factory for the Route Listener.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class RouteListenerFactory
{
    /**
     * Create Service
     *
     * @param ContainerInterface|ServiceLocatorInterface $container Zend Service Manager
     *
     * @return RouteListener
     */
    public function __invoke($container)
    {
        return new RouteListener(
            $container->get(SiteService::class),
            $container->get(RedirectService::class),
            $container->get(DomainRedirectService::class),
            $container->get(LocaleService::class)
        );
    }
}
