<?php
/**
 * Service Factory for the Plugin Manager
 *
 * This file contains the factory needed to generate a Plugin Manager.
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

use Rcm\Service\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Plugin Manager
 *
 * Factory for the Plugin Manager.
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
class PluginManagerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return PluginManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Zend\View\Renderer\PhpRenderer $viewRenderer */
        $viewRenderer = $serviceLocator->get('ViewRenderer');

        /** @var \Zend\Stdlib\RequestInterface $request */
        $request = $serviceLocator->get('request');

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache = $serviceLocator->get('Rcm\Service\Cache');

        $config = $serviceLocator->get('config');

        $viewEventManager = $serviceLocator->get('ViewManager')
            ->getView()
            ->getEventManager();


        return new PluginManager(
            $entityManager,
            $config,
            $serviceLocator,
            $viewRenderer,
            $request,
            $cache,
            $viewEventManager
        );
    }
}
