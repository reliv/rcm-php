<?php

namespace RcmUser\Log\Event\UserDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class DeleteUserFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteUserFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DeleteUserFailListener
     */
    public function __invoke($container)
    {
        return new DeleteUserFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
