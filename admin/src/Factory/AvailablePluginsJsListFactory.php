<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use RcmAdmin\View\Helper\AvailablePluginsJsList;
use Zend\ServiceManager\ServiceLocatorInterface;

class AvailablePluginsJsListFactory
{
    /**
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return AvailablePluginsJsList
     */
    public function __invoke($container)
    {
        $plugin = new AvailablePluginsJsList();
        $plugin->setServiceLocator($container);

        return $plugin;
    }
}
