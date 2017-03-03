<?php

namespace Rcm\Page\Renderer;

use Interop\Container\ContainerInterface;
use Rcm\Page\PageData\PageDataService;
use Rcm\Page\PageStatus\PageStatus;

/**
 * Class PageRendererBcFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageRendererBcFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return PageRendererBc
     */
    public function __invoke($container)
    {
        return new PageRendererBc(
            $container->get('Rcm\Service\LayoutManager'),
            $container->get(PageDataService::class),
            $container->get(PageStatus::class)
        );
    }
}
