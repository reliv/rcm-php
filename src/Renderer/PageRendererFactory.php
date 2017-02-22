<?php

namespace Rcm\Renderer;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

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
            $container->get('Doctrine\ORM\EntityManager'),
            $container->get('Rcm\Service\LayoutManager'),
            $container->get('Rcm\Acl\CmsPermissionsChecks'),
            $container->get(PageStatus::class)
        );
    }
}
