<?php

namespace Rcm\Factory;

use Rcm\Block\Config\ConfigRepository;
use Rcm\View\Helper\Container;
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
            \Rcm\Service\CurrentSite::class
        );

        // @GammaRelease
        $blockConfigRepository = $serviceLocator->get(
            ConfigRepository::class
        );

        return new Container(
            $currentSite,
            $pluginManager,
            $blockConfigRepository
        );
    }
}
