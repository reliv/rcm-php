<?php

namespace Rcm\Block\Renderer;

use Interop\Container\ContainerInterface;
use Rcm\Block\Config\ConfigRepository;

class RendererClientReactFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RendererClientReact($container->get(ConfigRepository::class));
    }
}
