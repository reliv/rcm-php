<?php

namespace RcmUser\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CurrentUserFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CurrentUserFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return CurrentUser
     */
    public function __invoke($serviceLocator)
    {
        /** @var \RcmUser\Authentication\Service\UserAuthenticationService $authServ */
        $authServ = $serviceLocator->get(
            \RcmUser\Authentication\Service\UserAuthenticationService::class
        );

        return new CurrentUser($authServ);
    }
}
