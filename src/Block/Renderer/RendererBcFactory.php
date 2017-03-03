<?php

namespace Rcm\Block\Renderer;

use Interop\Container\ContainerInterface;

class RendererBcFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RendererBc(
            $container,
            $container->get('ViewRenderer')
        );
    }
}
