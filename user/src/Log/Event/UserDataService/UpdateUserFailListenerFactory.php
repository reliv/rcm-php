<?php

namespace RcmUser\Log\Event\UserDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class UpdateUserFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UpdateUserFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return UpdateUserFailListener
     */
    public function __invoke($container)
    {
        return new UpdateUserFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
