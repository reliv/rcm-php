<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use RcmAdmin\Controller\ApiAdminSitePageCloneController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ApiAdminSitePageControllerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ApiAdminSitePageCloneControllerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $container
     *
     * @return ApiAdminSitePageCloneController
     */
    public function __invoke($container)
    {
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        return new ApiAdminSitePageCloneController($container);
    }
}
