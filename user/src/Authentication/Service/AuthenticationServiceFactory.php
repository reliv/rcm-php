<?php

namespace RcmUser\Authentication\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthenticationServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AuthenticationServiceFactory
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
        $storage = $serviceLocator->get(
            \RcmUser\Authentication\Storage\Session::class
        );
        $adapter = $serviceLocator->get(
            \RcmUser\Authentication\Adapter\Adapter::class
        );

        return new AuthenticationService($storage, $adapter);
    }
}
