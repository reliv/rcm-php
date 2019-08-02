<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class UpdateUserRolesSuccessListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UpdateUserRolesSuccessListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return UpdateUserRolesSuccessListener
     */
    public function __invoke($container)
    {
        return new UpdateUserRolesSuccessListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
