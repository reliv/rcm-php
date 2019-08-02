<?php

namespace RcmUser\Authentication\Storage;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserSessionFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserSessionFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserSession
     */
    public function __invoke($serviceLocator)
    {
        return new UserSession();
    }
}
