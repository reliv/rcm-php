<?php

namespace RcmUser\Acl\Service;

use Interop\Container\ContainerInterface;
use RcmUser\Event\UserEventManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthorizeServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AuthorizeServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return AuthorizeService
     */
    public function __invoke($serviceLocator)
    {
        $aclResourceService = $serviceLocator->get(
            \RcmUser\Acl\Service\AclResourceService::class
        );
        $aclDataService = $serviceLocator->get(
            \RcmUser\Acl\Service\AclDataService::class
        );

        $userEventManager = $serviceLocator->get(
            UserEventManager::class
        );

        $service = new AuthorizeService(
            $aclResourceService,
            $aclDataService,
            $userEventManager
        );

        return $service;
    }
}
