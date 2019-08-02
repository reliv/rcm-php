<?php

namespace RcmUser\Log\Event\AuthorizeService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class IsAllowedSuperAdminListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedSuperAdminListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return IsAllowedSuperAdminListener
     */
    public function __invoke($container)
    {
        return new IsAllowedSuperAdminListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
