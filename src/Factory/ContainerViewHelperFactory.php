<?php
/**
 * Service Factory for the Dispatch Listener
 *
 * This file contains the factory needed to generate a DispatchListener.
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

use Rcm\View\Helper\Container;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the DispatchListener
 *
 * Factory for the Dispatch Listener.
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
class ContainerViewHelperFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $viewServiceManager Zend View Helper Mgr
     *
     * @return Container
     */
    public function createService(ServiceLocatorInterface $viewServiceManager)
    {
        /** @var \Zend\View\HelperPluginManager $viewManager */
        $viewManager = $viewServiceManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $viewManager->getServiceLocator();

        /** @var \Rcm\Service\PluginManager $pluginManager */
        $pluginManager = $serviceLocator->get(
            'Rcm\Service\PluginManager'
        );

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get(
            'Rcm\Service\CurrentSite'
        );

        return new Container(
            $currentSite,
            $pluginManager
        );
    }
}
