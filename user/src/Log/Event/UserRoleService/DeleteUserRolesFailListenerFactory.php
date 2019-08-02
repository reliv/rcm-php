<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class DeleteUserRolesFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteUserRolesFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DeleteUserRolesFailListener
     */
    public function __invoke($container)
    {
        return new DeleteUserRolesFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
