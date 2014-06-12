<?php
/**
 * Service Factory for the Redirect Manager
 *
 * This file contains the factory needed to generate a Redirect Manager.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace Rcm\Factory;

use Rcm\Service\DomainManager;
use Rcm\Service\RedirectManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Redirect Manager
 *
 * Factory for the Redirect Manager.
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
class RedirectManagerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return DomainManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Redirect $redirectRepo */
        $redirectRepo = $entityManager->getRepository('\Rcm\Entity\Redirect');

        /** @var \Rcm\Service\SiteManager  $siteManager */
        $siteManager = $serviceLocator->get('Rcm\Service\SiteManager');

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache         = $serviceLocator->get('Rcm\Service\Cache');

        return new RedirectManager(
            $redirectRepo,
            $cache,
            $siteManager
        );

    }
}
