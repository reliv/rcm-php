<?php

namespace Rcm\Factory;

use Rcm\Service\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class PluginManagerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return PluginManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager = $serviceLocator->get('moduleManager');

        /** @var \Zend\View\Renderer\RendererInterface $viewRenderer */
        $viewRenderer  = $serviceLocator->get('ViewRenderer');

        /** @var \Zend\Stdlib\RequestInterface $request */
        $request       = $serviceLocator->get('request');

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache         = $serviceLocator->get('Rcm\\Service\\Cache');

        $config        = $serviceLocator->get('config');

        return new PluginManager(
            $entityManager,
            $config,
            $serviceLocator,
            $moduleManager,
            $viewRenderer,
            $request,
            $cache
        );
    }
}
