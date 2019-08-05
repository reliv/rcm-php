<?php

namespace RcmLogin\Log;

use Psr\Log\AbstractLogger;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class LoggerNone extends AbstractLogger implements Logger
{
    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        // NOOP - Logging nothing
    }
}
