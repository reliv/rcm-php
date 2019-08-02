<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class RemoveUserRoleSuccessListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RemoveUserRoleSuccessListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RemoveUserRoleSuccessListener
     */
    public function __invoke($container)
    {
        return new RemoveUserRoleSuccessListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
