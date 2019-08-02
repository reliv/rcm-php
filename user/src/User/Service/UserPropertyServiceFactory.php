<?php

namespace RcmUser\User\Service;

use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserPropertyServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserPropertyServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserPropertyService
     */
    public function __invoke($serviceLocator)
    {
        /** @var EventManagerInterface $eventManager */
        $eventManager = $serviceLocator->get(
            \RcmUser\Event\UserEventManager::class
        );

        $service = new UserPropertyService($eventManager);

        return $service;
    }
}
