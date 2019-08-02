<?php

namespace RcmUser\Log\Event\UserAuthenticationService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class ValidateCredentialsFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ValidateCredentialsFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return ValidateCredentialsFailListener
     */
    public function __invoke($container)
    {
        return new ValidateCredentialsFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
