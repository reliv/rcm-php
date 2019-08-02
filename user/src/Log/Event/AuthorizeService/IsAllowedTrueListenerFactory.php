<?php

namespace RcmUser\Log\Event\AuthorizeService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class IsAllowedTrueListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedTrueListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return IsAllowedTrueListener
     */
    public function __invoke($container)
    {
        return new IsAllowedTrueListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
