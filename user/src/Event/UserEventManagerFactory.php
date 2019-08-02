<?php

namespace RcmUser\Event;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserEventManagerFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class UserEventManagerFactory
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
        /** @var ListenerCollection $listeners */
        $listeners = $serviceLocator->get(
            \RcmUser\Event\ListenerCollection::class
        );

        $service = new UserEventManager(
            UserEventManager::class,
            $listeners
        );

        return $service;
    }
}
