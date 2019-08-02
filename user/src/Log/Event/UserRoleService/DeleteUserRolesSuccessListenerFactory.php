<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class DeleteUserRolesSuccessListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteUserRolesSuccessListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DeleteUserRolesSuccessListener
     */
    public function __invoke($container)
    {
        return new DeleteUserRolesSuccessListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
