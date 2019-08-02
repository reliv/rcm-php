<?php

namespace RcmUser\Authentication\Adapter;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserAdapterFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserAdapterFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserAdapter
     */
    public function __invoke($serviceLocator)
    {
        $userDataService = $serviceLocator->get(
            \RcmUser\User\Service\UserDataService::class
        );
        $encrypt = $serviceLocator->get(
            \RcmUser\User\Password\Password::class
        );
        $adapter = new UserAdapter(
            $userDataService,
            $encrypt
        );

        return $adapter;
    }
}
