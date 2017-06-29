<?php

namespace Rcm\Service;

use Rcm\Block\Config\ConfigRepository;
use Rcm\Block\Instance\InstanceRepository;
use Rcm\Block\InstanceWithData\InstanceWithDataService;
use Rcm\Block\Renderer\RendererService;
use Rcm\Page\Renderer\PageRendererBc;
use Rcm\Service\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PluginManagerFactory
{
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        return new PluginManager(
            $serviceLocator->get('Doctrine\ORM\EntityManager'),
            $serviceLocator->get('Config'),
            $serviceLocator,
            $serviceLocator->get('ViewRenderer'),
            $serviceLocator->get('request'),
            $serviceLocator->get(\Rcm\Service\Cache::class),
            $serviceLocator->get('ViewManager')
                ->getView()
                ->getEventManager(),
            $serviceLocator->get(RendererService::class),
            $serviceLocator->get(InstanceWithDataService::class),
            $serviceLocator->get(InstanceRepository::class),
            $serviceLocator->get(ConfigRepository::class)
        );
    }
}
