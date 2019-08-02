<?php

namespace RcmUser\Log;

use Zend\Log\LoggerInterface;

/**
 * Class AbstractLogger
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractLogger implements Logger
{
    /**
     * @var int $logLevel
     */
    protected $logLevel = \Zend\Log\Logger::ERR;

    /**
     * __construct
     *
     * @param int $logLevel logLevel
     */
    public function __construct($logLevel = \Zend\Log\Logger::ERR)
    {
        $this->setLogLevel($logLevel);
    }

    /**
     * setLogLevel
     *
     * @param int $logLevel logLevel
     *
     * @return void
     */
    protected function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * getLogLevel
     *
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * getLevel - get the level int from string
     *
     * @param string $type type
     *
     * @return mixed
     */
    protected function getLevel($type)
    {
        if (defined('\Zend\Log\Logger::' . $type)) {
            return constant('\Zend\Log\Logger::' . $type);
        }

        return $this->logLevel;
    }

    /**
     * canLog
     *
     * @param int $type type
     *
     * @return bool
     */
    public function canLog($type)
    {
        $level = $this->getLevel($type);

        if ($level > $this->getLogLevel()) {
            // no logging
            return false;
        }

        return true;
    }

    /**
     * log
     *
     * @param string $type    type
     * @param string $message message
     * @param array  $extra   extra
     *
     * @return LoggerInterface
     */
    abstract protected function log(
        $type,
        $message,
        $extra = []
    );

    /**
     * emerg
     *
     * @param string            $message message
     * @param array|\Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function emerg(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * alert
     *
     * @param string            $message message
     * @param array|\Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function alert(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * crit
     *
     * @param string            $message message
     * @param array|\Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function crit(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * err
     *
     * @param string            $message message
     * @param array|\Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function err(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * warn
     *
     * @param string            $message message
     * @param array|\Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function warn(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * notice
     *
     * @param string            $message message
     * @param array|\Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function notice(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * info
     *
     * @param string            $message message
     * @param array|\Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function info(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * debug
     *
     * @param string            $message message
     * @param array|\Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function debug(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }
}
