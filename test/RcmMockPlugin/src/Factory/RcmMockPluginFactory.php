<?php

namespace RcmMockPlugin\Factory;

use RcmMockPlugin\Controller\PluginController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory PluginController
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
class RcmMockPluginFactory
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $controllerManager Zend Controller Manager
     *
     * @return PluginController
     */
    public function __invoke($controllerManager)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $controllerMgr For IDE */
        $controllerMgr = $controllerManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerMgr->getServiceLocator();

        /** @var \Zend\Cache\Storage\Adapter\Memory $cache */
        $cache = $serviceLocator->get(\Rcm\Service\Cache::class);

        $controller = new PluginController(
            $cache
        );

        return $controller;
    }
}
