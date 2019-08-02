<?php

namespace RcmUser\User\Service;

use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserRoleServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserRoleServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return object
     */
    public function __invoke($serviceLocator)
    {
        /** @var EventManagerInterface $eventManager */
        $eventManager = $serviceLocator->get(
            \RcmUser\Event\UserEventManager::class
        );

        $userRolesDataMapper = $serviceLocator->get(
            \RcmUser\User\Db\UserRolesDataMapper::class
        );

        $service = new UserRoleService(
            $userRolesDataMapper,
            $eventManager
        );

        return $service;
    }
}
