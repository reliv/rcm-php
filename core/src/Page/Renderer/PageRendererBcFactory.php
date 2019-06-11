<?php

namespace Rcm\Page\Renderer;

use Interop\Container\ContainerInterface;
use Rcm\Page\PageData\PageDataService;
use Rcm\Page\PageStatus\PageStatus;
use Zend\Expressive\ZendView\ZendViewRenderer;

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
            $container->get(\Rcm\Service\LayoutManager::class),
            $container->get(PageDataService::class),
            $container->get(PageStatus::class),
            $container->get('viewrenderer'),
            $container->get(\RcmAdmin\Controller\AdminPanelController::class)
        );
    }
}
