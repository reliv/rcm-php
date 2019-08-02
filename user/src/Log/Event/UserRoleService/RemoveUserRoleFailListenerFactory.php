<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class RemoveUserRoleFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RemoveUserRoleFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RemoveUserRoleFailListener
     */
    public function __invoke($container)
    {
        return new RemoveUserRoleFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
