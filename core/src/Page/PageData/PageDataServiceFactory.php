<?php

namespace Rcm\Page\PageData;

use Interop\Container\ContainerInterface;
use Rcm\Api\Repository\Page\FindPage;
use Rcm\Page\PageStatus\PageStatus;

/**
 * @GammaRelease
 * Class PageDataServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageDataServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return PageDataService
     */
    public function __invoke($container)
    {
        return new PageDataService(
            $container->get(FindPage::class),
            $container->get(\Rcm\Acl\CmsPermissionChecks::class),
            $container->get(PageStatus::class)
        );
    }
}
