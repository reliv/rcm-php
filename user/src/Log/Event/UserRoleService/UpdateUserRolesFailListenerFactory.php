<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class UpdateUserRolesFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UpdateUserRolesFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return UpdateUserRolesFailListener
     */
    public function __invoke($container)
    {
        return new UpdateUserRolesFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
