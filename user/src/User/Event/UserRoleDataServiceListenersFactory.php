<?php

namespace RcmUser\User\Event;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserRoleDataServiceListenersFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserRoleDataServiceListenersFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserRoleDataServiceListeners
     */
    public function __invoke($serviceLocator)
    {
        $service = new UserRoleDataServiceListeners();

        $service->setUserRoleService(
            $serviceLocator->get(
                \RcmUser\User\Service\UserRoleService::class
            )
        );

        return $service;
    }
}
