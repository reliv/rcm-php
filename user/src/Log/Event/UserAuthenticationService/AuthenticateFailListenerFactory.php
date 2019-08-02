<?php

namespace RcmUser\Log\Event\UserAuthenticationService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class AuthenticateFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AuthenticateFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return AuthenticateFailListener
     */
    public function __invoke($container)
    {
        return new AuthenticateFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
