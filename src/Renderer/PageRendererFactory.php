<?php

namespace Rcm\Renderer;

use Interop\Container\ContainerInterface;
use Rcm\Service\PageRenderDataService;
use Rcm\Service\PageStatus;

/**
 * Class PageRendererFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageRendererFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return PageRenderer
     */
    public function __invoke($container)
    {
        return new PageRenderer(
            $container->get('Rcm\Service\LayoutManager'),
            $container->get(PageRenderDataService::class),
            $container->get(PageStatus::class)
        );
    }
}
