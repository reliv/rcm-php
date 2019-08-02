<?php

namespace RcmUser\Log\Event;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Config;

/**
 * Class LoggerListenersFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class LoggerListenersFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return LoggerListeners
     */
    public function __invoke($container)
    {
        $logConfig = $container->get(Config::class);

        return new LoggerListeners(
            $container,
            $logConfig->get(LoggerListeners::class, [])
        );
    }
}
