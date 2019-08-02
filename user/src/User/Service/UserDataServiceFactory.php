<?php

namespace RcmUser\User\Service;

use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserDataServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserDataServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserDataService
     */
    public function __invoke($serviceLocator)
    {
        $cfg = $serviceLocator->get(
            \RcmUser\User\Config::class
        );
        /** @var \RcmUser\User\Db\UserDataMapper $userDataMapper */
        $userDataMapper = $serviceLocator->get(
            \RcmUser\User\Db\UserDataMapper::class
        );

        /** @var EventManagerInterface $eventManager */
        $eventManager = $serviceLocator->get(
            \RcmUser\Event\UserEventManager::class
        );

        $service = new UserDataService($eventManager);
        $service->setDefaultUserState(
            $cfg->get(
                'DefaultUserState',
                null
            )
        );
        $service->setValidUserStates(
            $cfg->get(
                'ValidUserStates',
                []
            )
        );
        $service->setUserDataMapper($userDataMapper);

        return $service;
    }
}
