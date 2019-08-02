<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class RemoveUserRoleListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RemoveUserRoleListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RemoveUserRoleListener
     */
    public function __invoke($container)
    {
        return new RemoveUserRoleListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
