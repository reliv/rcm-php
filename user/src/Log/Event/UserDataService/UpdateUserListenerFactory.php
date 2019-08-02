<?php

namespace RcmUser\Log\Event\UserDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class UpdateUserListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UpdateUserListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return UpdateUserListener
     */
    public function __invoke($container)
    {
        return new UpdateUserListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
