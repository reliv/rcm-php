<?php

namespace RcmUser\Log\Event\UserRoleService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class AddUserRoleFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AddUserRoleFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return AddUserRoleFailListener
     */
    public function __invoke($container)
    {
        return new AddUserRoleFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
