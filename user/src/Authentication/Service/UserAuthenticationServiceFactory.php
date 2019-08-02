<?php

namespace RcmUser\Authentication\Service;

use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserAuthenticationServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserAuthenticationServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserAuthenticationService
     */
    public function __invoke($serviceLocator)
    {
        $config = $serviceLocator->get(
            \RcmUser\Authentication\Config::class
        );
        /** @var EventManagerInterface $eventManager */
        $eventManager = $serviceLocator->get(
            \RcmUser\Event\UserEventManager::class
        );

        $service = new UserAuthenticationService(
            $eventManager
        );

        $service->setObfuscatePassword(
            $config->get(
                'ObfuscatePasswordOnAuth',
                false
            )
        );

        return $service;
    }
}
