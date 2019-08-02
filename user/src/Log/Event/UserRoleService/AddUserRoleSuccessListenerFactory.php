<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class AddUserRoleSuccessListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AddUserRoleSuccessListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return AddUserRoleSuccessListener
     */
    public function __invoke($container)
    {
        return new AddUserRoleSuccessListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
