<?php
/**
 * Service Factory for the Container Manager
 *
 * This file contains the factory needed to generate a Container Manager.
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

use Rcm\Service\ContainerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Container Manager
 *
 * Factory for the Container Manager.
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
class ContainerManagerFactory implements FactoryInterface
{

    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return ContainerManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\SiteManager $siteManager */
        $siteManager   = $serviceLocator->get('Rcm\Service\SiteManager');

        /** @var \Rcm\Service\PluginManager $pluginManager */
        $pluginManager = $serviceLocator->get('Rcm\Service\PluginManager');

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Zend\Cache\Storage\StorageInterface $rcmCache */
        $rcmCache      = $serviceLocator->get('Rcm\Service\Cache');

        return new ContainerManager(
            $siteManager,
            $pluginManager,
            $entityManager,
            $rcmCache
        );
    }
}
