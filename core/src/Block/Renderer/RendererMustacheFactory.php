<?php

namespace Rcm\Block\Renderer;

use Interop\Container\ContainerInterface;
use Rcm\Block\Config\ConfigRepository;

class RendererMustacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RendererMustache($container->get(ConfigRepository::class));
    }
}
