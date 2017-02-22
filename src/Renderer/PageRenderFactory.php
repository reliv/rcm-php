<?php

namespace Rcm\Renderer;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * Class PageRenderFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageRenderFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return PageRender
     */
    public function __invoke($container)
    {
        return new PageRender(
            $container->get('Doctrine\ORM\EntityManager'),
            $container->get('Rcm\Service\LayoutManager'),
            $container->get('Rcm\Acl\CmsPermissionsChecks'),
            $container->get(PageStatus::class)
        );
    }
}
