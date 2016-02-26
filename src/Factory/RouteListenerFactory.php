<?php

namespace Rcm\Factory;

use Rcm\EventListener\RouteListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Ip;

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
class RouteListenerFactory implements FactoryInterface
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return RouteListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('Rcm\Service\CurrentSite');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Redirect $redirectRepo */
        $redirectRepo = $entityManager->getRepository('\Rcm\Entity\Redirect');

        $config = $serviceLocator->get('config');

        return new RouteListener(
            $currentSite,
            $redirectRepo,
            new Ip(),
            $config
        );
    }
}
