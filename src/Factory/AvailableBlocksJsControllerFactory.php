<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use Rcm\Service\SiteService;
use RcmAdmin\Controller\AvailableBlocksJsController;
use RcmAdmin\Service\RendererAvailableBlocksJs;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AvailableBlocksJsControllerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AvailableBlocksJsControllerFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface|ControllerManager
     *
     * @return AvailableBlocksJsController
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        return new AvailableBlocksJsController(
            $container->get(RendererAvailableBlocksJs::class),
            $container->get(\Rcm\Acl\CmsPermissionChecks::class),
            $container->get(SiteService::class)
        );
    }
}
