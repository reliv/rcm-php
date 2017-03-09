<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use Rcm\Service\SiteService;
use RcmAdmin\Controller\AvailableBlocksJsController;
use RcmAdmin\Service\RendererAvailableBlocksJs;

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
     * @param $controllerManager
     *
     * @return AvailableBlocksJsController
     */
    public function __invoke($controllerManager)
    {
        $container = $controllerManager->getServiceLocator();

        return new AvailableBlocksJsController(
            $container->get(RendererAvailableBlocksJs::class),
            $container->get('Rcm\Acl\CmsPermissionsChecks'),
            $container->get(SiteService::class)
        );
    }
}
