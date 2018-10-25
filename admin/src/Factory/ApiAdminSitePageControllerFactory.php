<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use RcmAdmin\Controller\ApiAdminSitePageController;
use Zend\Mvc\Controller\ControllerManager;
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
        // @BC for ZendFramework
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        return new ApiAdminSitePageController($container);
    }
}
