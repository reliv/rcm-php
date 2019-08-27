<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\SiteSecureRepo;
use RcmAdmin\Controller\ApiAdminManageSitesController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiAdminManageSitesControllerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $container
     *
     * @return ApiAdminManageSitesController
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        return new ApiAdminManageSitesController($container->get(RequestContext::class)->get(SiteSecureRepo::class));
    }
}
