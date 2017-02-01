<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use RcmAdmin\Controller\ApiAdminSitePageController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ApiAdminSitePageControllerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ApiAdminSitePageControllerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $container
     *
     * @return ApiAdminSitePageController
     */
    public function __invoke($container)
    {
        return new ApiAdminSitePageController($container);
    }
}
