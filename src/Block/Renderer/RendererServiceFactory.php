<?php

namespace Rcm\Block\Renderer;

use Interop\Container\ContainerInterface;

/**
 * Class RendererServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RendererService
     */
    public function __invoke($container)
    {
        return new RendererService(
            $container->get(RendererRepository::class)
        );
    }
}
