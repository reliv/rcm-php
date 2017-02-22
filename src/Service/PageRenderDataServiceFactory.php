<?php

namespace Rcm\Service;

use Interop\Container\ContainerInterface;

/**
 * Class PageRenderDataServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageRenderDataServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return PageRenderDataService
     */
    public function __invoke($container)
    {
        return new PageRenderDataService(
            $container->get('Doctrine\ORM\EntityManager'),
            $container->get('Rcm\Acl\CmsPermissionsChecks'),
            $container->get(PageStatus::class)
        );
    }
}
