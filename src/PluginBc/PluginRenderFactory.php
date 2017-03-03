<?php

namespace PluginBc;

use Interop\Container\ContainerInterface;

class PluginRenderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new PluginRenderer(
            $container,
            $container->get('ViewRenderer')
        );
    }
}
