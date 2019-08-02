<?php

namespace RcmUser\Log\Event\AclDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class DeleteAclRoleListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteAclRoleListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DeleteAclRoleListener
     */
    public function __invoke($container)
    {
        return new DeleteAclRoleListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
