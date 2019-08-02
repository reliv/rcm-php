<?php

namespace RcmUser\User\Event;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserDataServiceListenersFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserDataServiceListenersFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserDataServiceListeners
     */
    public function __invoke($serviceLocator)
    {
        $dm = $serviceLocator->get(
            \RcmUser\User\Db\UserDataMapper::class
        );

        $service = new UserDataServiceListeners();

        $service->setUserDataMapper($dm);

        return $service;
    }
}
