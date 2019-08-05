<?php

namespace RcmLogin\Log;

use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class LoggerNoneFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return LoggerNone
     */
    public function __invoke($container)
    {
        return new LoggerNone();
    }
}
