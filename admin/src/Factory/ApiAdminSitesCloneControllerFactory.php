<?php

namespace RcmAdmin\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Acl\GetCurrentUser;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\SiteSecureRepo;
use RcmAdmin\Controller\ApiAdminManageSitesController;
use RcmAdmin\Controller\ApiAdminSitesCloneController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiAdminSitesCloneControllerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $container
     *
     * @return ApiAdminSitesCloneController
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        return new ApiAdminSitesCloneController(
            $container->get(RequestContext::class)->get(SiteSecureRepo::class),
            $container->get(EntityManager::class),
            $container->get(RequestContext::class)->get(GetCurrentUser::class)
        );
    }
}
