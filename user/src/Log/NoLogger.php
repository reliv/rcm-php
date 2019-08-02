<?php

namespace RcmUser\Log;

/**
 * Class NoLogger
 *
 * NoLogger
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Log
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class NoLogger extends AbstractLogger implements Logger
{
    /**
     * log
     *
     * @param string $type
     * @param string $message
     * @param array  $extra
     *
     * @return Logger
     */
    protected function log(
        $type,
        $message,
        $extra = []
    ) {
        // NO LOGGING
        return $this;
    }
}
