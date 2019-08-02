<?php

namespace RcmUser\Log\Event\AuthorizeService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class IsAllowedErrorListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedErrorListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return IsAllowedErrorListener
     */
    public function __invoke($container)
    {
        return new IsAllowedErrorListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
