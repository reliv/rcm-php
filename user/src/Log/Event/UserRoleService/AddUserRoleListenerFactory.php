<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class AddUserRoleListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AddUserRoleListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return AddUserRoleListener
     */
    public function __invoke($container)
    {
        return new AddUserRoleListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
