<?php

namespace RcmAdmin\Factory;

use RcmAdmin\View\Helper\AvailablePluginsJsList;

class AvailablePluginsJsListFactory
{
    public function __invoke($services)
    {
        $plugin = new AvailablePluginsJsList();
        $plugin->setServiceLocator($services);

        return $plugin;
    }
}
