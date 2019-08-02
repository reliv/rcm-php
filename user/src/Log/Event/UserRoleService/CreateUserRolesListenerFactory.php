<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class CreateUserRolesListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CreateUserRolesListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return CreateUserRolesListener
     */
    public function __invoke($container)
    {
        return new CreateUserRolesListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
