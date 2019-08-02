<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class CreateUserRolesFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CreateUserRolesFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return CreateUserRolesFailListener
     */
    public function __invoke($container)
    {
        return new CreateUserRolesFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
