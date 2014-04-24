<?php
/**
 * Service Factory for the Site Manager
 *
 * This file contains the factory needed to generate a Site Manager.
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
 * @link      http://reliv.com
 */
namespace Rcm\Factory;

use Rcm\Service\SiteManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Site Manager
 *
 * Factory for the Site Manager.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://reliv.com
 *
 */
class SiteManagerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return SiteManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\DomainManager $domainManager */
        $domainManager = $serviceLocator->get('Rcm\Service\DomainManager');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache         = $serviceLocator->get('Rcm\Service\Cache');

        return new SiteManager(
            $domainManager,
            $entityManager,
            $cache
        );
    }
}
