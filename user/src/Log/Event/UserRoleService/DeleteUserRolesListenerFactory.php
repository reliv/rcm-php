<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class DeleteUserRolesListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteUserRolesListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DeleteUserRolesListener
     */
    public function __invoke($container)
    {
        return new DeleteUserRolesListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
