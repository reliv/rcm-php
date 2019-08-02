<?php

namespace RcmUser\Authentication\Event;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserAuthenticationServiceListenersFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserAuthenticationServiceListenersFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserAuthenticationServiceListeners
     */
    public function __invoke($serviceLocator)
    {
        $auth = $serviceLocator->get(
            \RcmUser\Authentication\Service\AuthenticationService::class
        );

        $service = new UserAuthenticationServiceListeners(
            $auth
        );

        return $service;
    }
}
